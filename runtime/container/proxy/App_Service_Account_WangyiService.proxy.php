<?php

declare (strict_types=1);
namespace App\Service\Account;

use App\Extend\Wangyi\ServerAPI;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
class WangyiService extends BaseService
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
     * @var ServerAPI
     */
    private $serverAPI;
    /**
     * @func 创建用户
     * @author luzhenyu 2021/7/21
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/7/21
     * @param mixed $accid
     * @param mixed $name
     */
    public function createUser($accid, $name)
    {
        return $this->serverAPI->createAccid($accid, $name);
    }
}