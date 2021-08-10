<?php

declare (strict_types=1);
namespace App\Middleware;

use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Service\SqlInfoAspect;
use Hyperf\Di\Annotation\Inject;
use App\Queue\WriteSqlInfoQueue;
class SqlInfo implements MiddlewareInterface
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    protected $driver;
    public static $count = 0;
    /**
     * @inject
     * @var ContainerInterface
     */
    protected $container;
    public function __construct(ContainerInterface $container, DriverFactory $driverFactory)
    {
        $this->__handlePropertyHandler(__CLASS__);
        $this->container = $container;
        $this->driver = $driverFactory->get('default');
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        echo '-----------------------' . Coroutine::parentId();
        var_dump('---1-------------------------------');
        //        $uniqueValue = $this->getUniqueValue();
        //        var_dump($uniqueValue);
        //        $sqlInfoAspect = new SqlInfoAspect($uniqueValue);
        //
        //        if(isset($sqlInfoAspect::$sqlArr[$uniqueValue])){
        //            var_dump($sqlInfoAspect::$sqlArr[$uniqueValue]);
        //        }
        //        var_dump($sqlInfoAspect::$sqlArr);
        //        $getOrPost = $request->getMethod();
        //        if($getOrPost=="GET"){
        //            $headers = $request->getHeaders();
        //            $params = $headers['refer'] ?? [];
        //        }
        //        if($getOrPost=="POST"){
        //            $params = $request->getParsedBody();
        //        }
        //        $message = "------------------{$getOrPost}------{$request->getRequestTarget()}----------";
        //        $this->container->get(LoggerFactory::class)->make('sql')->info($message,[$params]);
        return $handler->handle($request);
    }
    /**
     * @return string
     * @note  获取唯一值
     * @author   yangWanZhang   2021/8/6
     * @email  wz_yang@juling.vip
     * @modifier yangWanZhang 2021/8/6
     */
    public function getUniqueValue() : string
    {
        return md5(uniqid((string) microtime(true), true));
    }
}