<?php

declare (strict_types=1);
namespace App\Actions\Pay\Alipay;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\AliPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 生成支付宝支付二维码，用支付宝扫码后支付.
 * @author yulu 2021/07/15
 * @email l_yu@juling.vip
 */
class CreatePayUrl extends AbstractAction
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
     * @api {post} /pay/alipay/createPayUrl 生成微信支付二维码，用微信扫码后支付
     * @apiDescription 生成支付宝支付二维码  对应文档  https://doc.yurunsoft.com/PaySDK/73 官方文档https://opendocs.alipay.com/apis/api_1/alipay.trade.create
     * @apiName createPayUrl
     * @apiGroup 支付宝支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号
     * @apiParam {string}   goodsInfo   商品描述
     * @apiParam {int}      totalFee    订单金额
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {int} data.payUrl 调用微信的支付链接，用于生成二维码
     * @apiSuccessExample {json}
     * {"code":200000,"message":"","data":{"payUrl":"https:\/\/qr.alipay.com\/bax05530uau42lplrh5q006b"}}
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
        //校验参数合法性
        $tradeNo = $this->request->input('tradeNo');
        if (empty($tradeNo)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'tradeNo?');
        }
        $goodsInfo = $this->request->input('goodsInfo');
        if (empty($goodsInfo)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'goodsInfo?');
        }
        $totalFee = (int) $this->request->input('totalFee');
        if (empty($totalFee)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'totalFee?');
        }
        $res = $this->aliPayService->createPayUrl($tradeNo, $goodsInfo, $totalFee);
        if ($res['ok']) {
            return ['payUrl' => $res['payUrl']];
        }
        throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $res['msg']);
    }
}