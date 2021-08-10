<?php

declare (strict_types=1);
namespace App\Actions\Pay\Alipay;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\AliPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 创建APP订单.
 * @author yulu 2021/07/15
 * @email l_yu@juling.vip
 */
class CreateAppOrder extends AbstractAction
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
     * @api {post} /pay/alipay/createAppOrder 创建APP订单
     * @apiDescription 创建APP订单 https://doc.yurunsoft.com/PaySDK/163  https://opendocs.alipay.com/open/204/105465/
     * @apiName createAppOrder
     * @apiGroup 支付宝支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号
     * @apiParam {string}   goodsInfo   商品描述
     * @apiParam {int}      totalFee    订单金额  分
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.orderInfo app调用支付需要的信息
     * @apiSuccessExample {json}
     * {"code":200000,"message":"","data":{"orderInfo":"format=JSON&charset=UTF-8&sign_type=RSA2&version=1.0&method=alipay.trade.app.pay&notify_url=http%3A%2F%2Fmemberapi.devhot.139622.com%2Fapi%2Fnotify%2Fali&timeout_express=5m&app_id=2016100902062462&biz_content=%7B%22subject%22%3A%22%5Cu6d4b%5Cu8bd5%5Cu5546%5Cu54c1%22%2C%22out_trade_no%22%3A%22111111111113333%22%2C%22total_amount%22%3A0.01%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22goods_type%22%3A1%7D&timestamp=2021-07-15+15%3A30%3A38&sign="}}
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
        $orderInfo = $this->aliPayService->creatAppOrder($tradeNo, $goodsInfo, $totalFee);
        return ['orderInfo' => $orderInfo];
    }
}