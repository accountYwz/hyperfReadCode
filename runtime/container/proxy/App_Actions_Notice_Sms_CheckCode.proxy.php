<?php

declare (strict_types=1);
namespace App\Actions\Notice\Sms;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Model\Main\Sms;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use App\Service\Notice\NoticeSmsService;
use Hyperf\Di\Annotation\Inject;
class CheckCode extends AbstractAction
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
     * @Inject()
     * @var NoticeSmsService
     */
    public $noticeSmsService;
    /**
     * @Inject()
     * @var Sms
     */
    private $smsModel;
    /**
     * @api {POST} /notice/sms/checkCode  短信校验
     * @apiDescription 短信校验  杨万章 2021/07/20
     * @apiName checkCode
     * @apiGroup 账户相关
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {String} tel 手机号
     * @apiParam {String} type 类型  1-注册 2-修改密码 3-异常登录 4-修改密保手机 5-身份校验 6 - 更换绑定手机
     * @apiParam {String} code 验证码
     * @apiSuccess {object} data 响应内容
     * @apiSuccessExample {json}
     *{"code":200000,"message":"","data":{}}
     * @apiError {int} code 请求状态码，非200
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":400001,"message":"手机号或验证码不能为空","data":{}}
     *{"code":400001,"message":"短信类型错误","data":{}}
     *{"code":400001,"message":"验证码输入有误，请核对后重新输入","data":{}}
     *{"code":400001,"message":"您输入的验证码已过期，请重新获取","data":{}}
     */
    public function handle()
    {
        $tel = $this->request->input('tel');
        // 手机号
        $code = $this->request->input('code');
        // 验证码
        $type = $this->request->input('type', 0, 'intval');
        // 短信类型错误
        if (empty($tel) || empty($code)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, '手机号或验证码不能为空');
        }
        if (!preg_match("/^1[34578]\\d{9}\$/", $tel)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, '手机号格式错误');
        }
        if ($type <= 0) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, '短信类型错误');
        }
        $sms = $this->smsModel->getSms($tel, $type);
        if (!$sms || $sms['code'] != $code) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, '验证码输入有误，请核对后重新输入');
        }
        if ($sms['expire_time'] < time()) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, '您输入的验证码已过期，请重新获取');
        }
        return [];
    }
}