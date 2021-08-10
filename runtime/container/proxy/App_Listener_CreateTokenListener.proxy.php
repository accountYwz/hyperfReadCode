<?php

declare (strict_types=1);
namespace App\Listener;

use App\Event\UserLogined;
use App\Service\Account\DeviceService;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
/**
 * @Listener
 */
class CreateTokenListener implements ListenerInterface
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
    public function listen() : array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [UserLogined::class];
    }
    /**
     * @param UserLogined $event
     */
    public function process(object $event)
    {
        // 事件触发后该监听器要执行的代码写在这里，比如该示例下的发送用户注册成功短信等
        // 直接访问 $event 的 user 属性获得事件触发时传递的参数值
        // $event->user;
        $deviceService = $this->container->get(DeviceService::class);
        $deviceService->createToken($event->uid);
    }
}