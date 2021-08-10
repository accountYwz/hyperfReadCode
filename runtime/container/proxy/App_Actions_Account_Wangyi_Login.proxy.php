<?php

declare (strict_types=1);
namespace App\Actions\Account\Wangyi;

use App\Actions\AbstractAction;
use App\Service\Account\WangyiService;
use Hyperf\Di\Annotation\Inject;
class Login extends AbstractAction
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
     * @var WangyiService
     */
    private $wangyiService;
    /**
     * @api {get} /account/Wangyi/login 网易云信登录
     * @apiDescription 网易账号登录（未完成）luzhenyu 2021/07/21
     * @apiName account-wangyi-login
     * @apiGroup 账户相关
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.code code
     * @apiSuccessExample {json}
     *{"code":200000,"message":"","data":{"code":414,"desc":"already register"}}
     * @apiError {int} code 请求状态码，非200
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":200000,"message":"","data":{"code":414,"desc":"already register"}}
     */
    public function handle()
    {
        return $this->wangyiService->createUser('life_110001', 'aaa');
    }
}