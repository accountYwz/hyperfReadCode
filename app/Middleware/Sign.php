<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\ApiCheckService;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Sign implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    //不需要验签的路由
    private $notCheckPath = [
        'app/system/getSystemInfo',
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //不需要验签，直接返回 默认需要验签
        $appDebug = env('APP_DEBUG', false);
        if ($appDebug) {
            return $handler->handle($request);
        }
        $requestTarget = trim($request->getRequestTarget(), '/');
        //不校验的路径直接返回
        if (in_array($requestTarget, $this->notCheckPath)) {
            return $handler->handle($request);
        }
        //需要验签的接口
        $result = [];
        $result['res'] = true;
        //区分请求是不是get，post或者put
        $method = $request->getMethod();
        $signParams = [];
        if ($method == 'GET') {
            $signParams = $request->getQueryParams();
        } else {
            $signParams = $request->getParsedBody();
        }
        $result = make(ApiCheckService::class)->checkSignature($signParams);
        $errorCode = 0;
        //验签成功
        if ($result['res']) {
            //防重发攻击 Replay attack
            $checkResult = make(ApiCheckService::class)->checkReplayAttack($signParams);
            if ($checkResult['res']) {
                return $handler->handle($request);
            }
            $errorCode = $checkResult['errorCode'];
        } else {
            $errorCode = $result['errorCode'];
        }
        throw new BusinessException($errorCode);
    }
}
