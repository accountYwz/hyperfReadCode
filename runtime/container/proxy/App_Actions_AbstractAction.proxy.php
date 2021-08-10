<?php

declare (strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Actions;

use App\Service\User\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
abstract class AbstractAction
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @func 控制器入口抽象方法
     * @author luzhenyu 2021/7/22
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/22
     */
    public abstract function handle();
}