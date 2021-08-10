<?php

declare(strict_types=1);

namespace App\Middleware\Redirect;

use App\Constants\CodeMsg;
use App\Service\Account\DeviceService;
use App\Service\User\UserService;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * 登录状态下可通行
 */
class Logged implements MiddlewareInterface
{
    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var DeviceService
     */
    protected $deviceService;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = isset($request->getQueryParams()['token']) ? $request->getQueryParams()['token'] :
            (isset($request->getParsedBody()['token']) ? $request->getParsedBody()['token'] : '');
        $userInfo = $this->deviceService->checkToken($token);
        if ($userInfo) {
            $this->userService->setUserInfo($userInfo);
            return $handler->handle($request);
        }
        throw new BusinessException(CodeMsg::TOKEN_ERROE);
    }
}