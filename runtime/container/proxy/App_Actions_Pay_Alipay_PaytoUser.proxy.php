<?php

declare (strict_types=1);
namespace App\Actions\Pay\Alipay;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\AliPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 企业付款到用户零钱.
 * @author yulu 2021/7/16
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
     * @var AliPayService
     */
    private $aliPayService;
    /**
     * @api {get} /pay/alipay/payToUser 企业付款到用户零钱
     * @apiDescription 创建公众号订单  对应文档  https://doc.yurunsoft.com/PaySDK/124  官方文档：https://docs.open.alipay.com/api_28/alipay.fund.trans.toaccount.transfer/
     * @apiName payToUser
     * @apiGroup 支付宝支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号     订单号和交易流水号二选一
     * @apiParam {string}   account     收款方账户
     * @apiParam {int}      totalFee      付款金额 单位分
     * @apiParam {string}   desc        付款描述
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.result 支付状态 SUCCES
     * @apiSuccessExample {json}
     * {"code":200000,"message":"请求成功","data":{"result":"SUCCESS"}}
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
        $account = $this->request->input('account');
        if (empty($account)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'account?');
        }
        $totalFee = (int) $this->request->input('totalFee');
        if (empty($totalFee)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'totalFee?');
        }
        //订单描述
        $desc = $this->request->input('desc');
        if (empty($desc)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'desc?');
        }
        try {
            $res = $this->aliPayService->payToUser($tradeNo, $account, $totalFee, $desc);
            if ($res['ok']) {
                return ['result' => 'SUCCESS'];
            }
            throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $res['msg']);
        } catch (\Exception $e) {
            throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $e->getMessage());
        }
    }
}