<?php

namespace Lovetrytry\Jichukuangjia\Middleware;


use Lovetrytry\Jichukuangjia\Response as LoveResponse;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Response implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Constructs a new instance.
     *
     * @author     lovetrytry@yeah.net
     *
     * @param      \Psr\Container\ContainerInterface  $container  The container
     * @param      \Hyperf\Contract\ConfigInterface   $config     The configuration
     */
    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * { function_description }
     *
     * @author     lovetrytry@yeah.net
     *
     * @param      \Psr\Http\Message\ServerRequestInterface  $request  The request
     * @param      \Psr\Http\Server\RequestHandlerInterface  $handler  The handler
     *
     * @return     ResponseInterface                         The response interface.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 承接上下文
        Context::set(ServerRequestInterface::class, $request);

        $response = $handler->handle($request);

        return (new LoveResponse($response, $this->config))->handle();
    }
}
