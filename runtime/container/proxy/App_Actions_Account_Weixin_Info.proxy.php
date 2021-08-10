<?php

declare (strict_types=1);
namespace App\Actions\Account\Weixin;

use App\Actions\AbstractAction;
use App\Service\Account\WeixinService;
use Hyperf\Di\Annotation\Inject;
/**
 * 获取微信账号信息.
 * @author luzhenyu 2021/6/15
 * @email zy_lu@juling.vip
 */
class Info extends AbstractAction
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
     * @var WeixinService
     */
    private $weixinService;
    /**
     * @api {get} /account/weixin/info 获取微信账号信息
     * @apiDescription 获取微信账号信息，微信昵称，头像等 luzhenyu 2021/07/21
     * @apiName info1
     * @apiGroup 账户资料服务
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {String} token 用户标识
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {int} data.uid 用户ID
     * @apiSuccess {string} data.nickname 昵称
     * @apiSuccess {int} data.sex 性别
     * @apiSuccess {string} data.headimgurl 头像
     * @apiSuccessExample {json}
     * {"code":200,"message":"请求成功","data":{"uid":1245909,"nickname":"啊啊啊","sex":0,"headimgurl":""}}
     * @apiError {int} code 请求状态码，非200
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":500000,"message":"服务错误|401002token错误","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"code":400001,"message":"参数错误|token?","data":[]}
     */
    public function handle()
    {
        $uid = getUid();
        $accountInfo = $this->weixinService->getWeixinAccountInfo($uid);
        return ['uid' => (int) $accountInfo['uid'], 'nickname' => (string) $accountInfo['nickname'], 'sex' => (int) $accountInfo['sex'], 'headimgurl' => (string) $accountInfo['headimgurl']];
    }
}