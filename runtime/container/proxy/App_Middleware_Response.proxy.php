<?php

declare (strict_types=1);
namespace App\Middleware;

use App\Constants\ContextKey;
use App\Service\LogService;
use App\Service\SqlInfoAspect;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Coroutine;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Di\Annotation\Inject;
class Response implements MiddlewareInterface
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    /**
     * @inject
     * @var LogService
     */
    protected LogService $logService;
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var DriverInterface
     */
    protected $driver;
    /**
     * @var RequestInterface
     */
    protected $request;
    public function __construct(ContainerInterface $container, DriverFactory $driverFactory)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->container = $container;
        $this->request = $container->get(RequestInterface::class);
        $this->driver = $driverFactory->get('default');
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $response = $handler->handle($request);
        $this->handleSqlInfo($request);
        /**
         * 强制重置为 https 协议
         * */
        if ($response->hasHeader("Location")) {
            $location = $response->getHeaderLine("Location");
            $replaceLocation = str_replace("http://", "https://", $location);
            $response = $response->withHeader("Location", $replaceLocation);
            Context::set(ResponseInterface::class, $response);
        }
        return $response;
    }
    /**
     * @@Notes:异步写入文件日志
     * @@Author:yangWanZhang
     * @@email  wz_yang@juling.vip
     * @@Date: 2021/8/8
     * @@Modify:yangWanZhang
     */
    public function handleSqlInfo(ServerRequestInterface $request)
    {
        $uniqueValue = Context::get(ContextKey::CO_CONTEXT);
        $count = Context::get($uniqueValue . '_count');
        for ($i = 0; $i < $count; $i++) {
            $sql[] = Context::get($uniqueValue . '_' . $i);
        }
        if (!empty($sql)) {
            $getOrPost = $request->getMethod();
            if ($getOrPost == "GET") {
                $headers = $request->getHeaders();
                $params = $headers['refer'] ?? [];
            }
            if ($getOrPost == "POST") {
                $params = $request->getParsedBody();
            }
            $method = "------------------{$getOrPost}------{$request->getRequestTarget()}----------";
            array_unshift($sql, json_encode($params));
            array_unshift($sql, $method);
            co(function () use($sql) {
                foreach ($sql as $sqlInfo) {
                    $this->logService->writeSqlLog($sqlInfo);
                }
            });
        }
    }
}