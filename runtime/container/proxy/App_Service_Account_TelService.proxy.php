<?php

declare (strict_types=1);
namespace App\Service\Account;

use App\Event\UserLogined;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
class TelService extends BaseService
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct(...func_get_args());
        }
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @func 手机号登录
     * @param string $tel 手机号
     * @param string $password 密码
     * @return int 用户ID
     * @author luzhenyu 2021/6/15
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/6/15
     */
    public function login(string $tel, string $password) : int
    {
        $uid = 110001;
        $this->eventDispatcher->dispatch(new UserLogined($uid));
        return $uid;
    }
}