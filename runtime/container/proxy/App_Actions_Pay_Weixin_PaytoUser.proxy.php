<?php

declare (strict_types=1);
namespace App\Actions\Pay\Weixin;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\WeixinPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 企业付款到用户零钱.
 * @author yulu 2021/7/14
 * @email l_yu@juling.vip
 */
class PaytoUser extends AbstractAction
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
     * @var WeixinPayService
     */
    private $weixinPayService;
    /**
     * @api {get} /pay/weixin/payToUser 企业付款到用户零钱
     * @apiDescription 企业付款到用户零钱   对应文档 https://doc.yurunsoft.com/PaySDK/112  官方文档：https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
     * @apiName queryOrder
     * @apiGroup 微信支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号     订单号和交易流水号二选一
     * @apiParam {string}   openId      用户微信openid
     * @apiParam {int}      amount      付款金额 单位分
     * @apiParam {string}   desc        付款描述
     * @apiParam {desc}     amount      付款金额 单位分
     * @apiParam {int}      isCheckName 是否校验用户真实姓名 0不校验  1校验 默认0
     * @apiParam {string}   userName    用户真实姓名
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.resultCode 支付状态 SUCCESS成功 FAIL失败
     * @apiSuccess {string} data.errCode  错误码
     * @apiSuccess {string} data.errCodeDes 错误描述
     * @apiSuccessExample {json}
     * {"code":200000,"message":"请求成功","data":{"resultCode":"SUCCESS","errCode":"","errCodeDes":""}}
     * @apiError {int} code 请求状态码，非200000
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":500000,"message":"服务错误|调用接口错误信息","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"code":400001,"message":"参数错误|token?","data":[]}
     */
    public function handle()
    {
        $tradeNo = $this->request->input('tradeNo');
        if (empty($tradeNo)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'tradeNo?');
        }
        $openId = $this->request->input('openId');
        if (empty($openId)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'openId?');
        }
        $amount = (int) $this->request->input('amount');
        if (empty($amount)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'amount?');
        }
        //订单描述
        $desc = $this->request->input('desc');
        if (empty($desc)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'desc?');
        }
        //默认不校验
        $isCheckName = (int) $this->request->input('isCheckName', 0);
        //是否校验用户姓名
        $userName = $this->request->input('userName', '');
        $res = $this->weixinPayService->payToUser($tradeNo, $openId, $amount, $desc, $isCheckName, $userName);
        if ($res['ok']) {
            return ['resultCode' => $res['resultCode'], 'errCode' => $res['errCode'], 'errCodeDes' => $res['errCodeDes']];
        }
        throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $res['msg']);
    }
}