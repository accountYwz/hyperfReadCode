<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ContextKey;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Coroutine;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Request implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get(RequestInterface::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uniqueValue = $this->getUniqueValue();
        Context::set(ContextKey::CO_CONTEXT, $uniqueValue);
        Context::set($uniqueValue . '_count', 0);
        /**
         * 强制重置为 https 协议
         * */
        $request = $request->withUri($request->getUri()->withScheme("https"));
        Context::set(ServerRequestInterface::class, $request);
        return $handler->handle($request);
    }

    /**
     * @return string
     * @@Notes: 获取全局的唯一值
     * @@Author:yangWanZhang
     * @@email  wz_yang@juling.vip
     * @@Date: 2021/8/8
     * @@Modify:yangWanZhang
     */
    private function getUniqueValue(): string
    {
        return md5(uniqid((string)microtime(true), true));
    }
}