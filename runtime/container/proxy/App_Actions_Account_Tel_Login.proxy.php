<?php

declare (strict_types=1);
namespace App\Actions\Account\Tel;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Account\DeviceService;
use App\Service\Account\TelService;
use App\Service\User\UserService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
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
    /** @Inject */
    private TelService $telService;
    /** @Inject */
    private DeviceService $deviceService;
    /** @Inject */
    private UserService $userService;
    /**
     * @api {get} /account/tel/login 手机号登录
     * @apiDescription 手机号密码登录 luzhenyu 2021/07/21
     * @apiName account-tel-login
     * @apiGroup 账户相关
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {String} tel 手机号
     * @apiParam {String} password 密码
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.token token
     * @apiSuccess {int} data.tokenExpireSec token过期剩余秒数
     * @apiSuccess {int} data.userInfo.id 用户ID
     * @apiSuccess {int} data.userInfo.account 用户账号
     * @apiSuccess {int} data.userInfo.status 用户状态
     * @apiSuccessExample {json}
     *{"code":200000,"message":"","data":{"token":"60f4ea68b2cfa","tokenExpireTime":1626749928,"userInfo":{"id":110001,"account":123456,"status":1}}}
     * @apiError {int} code 请求状态码，非200
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":400001,"message":"tel?password?","data":{}}
     */
    public function handle()
    {
        $tel = $this->request->input('tel');
        $password = $this->request->input('password');
        if (empty($tel) || empty($password)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'tel?password?');
        }
        $uid = $this->telService->login($tel, $password);
        if (!$uid) {
            throw new BusinessException(CodeMsg::UNKNOWN_ERROR, '用户ID异常');
        }
        $resData = $this->deviceService->getTokenInfo();
        $resData['userInfo'] = $this->userService->getUserInfo($uid);
        return $resData;
    }
}