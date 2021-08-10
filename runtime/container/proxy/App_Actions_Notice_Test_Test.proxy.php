<?php

declare (strict_types=1);
namespace App\Actions\Notice\Test;

use App\Actions\AbstractAction;
use App\ErrorCode\CodeMsg;
use App\Model\Main\Sms;
use http\Exception;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use App\Service\Notice\NoticeSmsService;
use Hyperf\Di\Annotation\Inject;
class Test extends AbstractAction
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
     * @api {POST} /notice/test/sendSms 发送短信
     * @apiDescription 发送短信   杨万章 2021/07/20
     * @apiName sendSms
     * @apiGroup 账户相关
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {String} tel 手机号
     * @apiParam {String} type 类型  1-注册 2-修改密码 3-异常登录 4-修改密保手机 5-身份校验 6 - 更换绑定手机
     * @apiSuccess {object} data 响应内容
     * @apiSuccessExample {json}
     *{"code":200000,"message":"","data":{}}
     * @apiError {int} code 请求状态码，非200
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":400001,"message":"发送失败","data":{}}
     *{"code":400001,"message":"参数错误","data":{}}
     *{"code":400001,"message":"网络虚拟手机号无法获取验证码","data":{}}
     *{"code":400001,"message":"手机号格式错误","data":{}}
     */
    public function handle()
    {
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        $this->smsModel->query()->where(['id' => 1])->first();
        $this->smsModel->query()->where(['id' => 2])->first();
        return [];
    }
}