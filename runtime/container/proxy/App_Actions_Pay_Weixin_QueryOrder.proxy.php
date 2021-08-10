<?php

declare (strict_types=1);
namespace App\Actions\Pay\Weixin;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\WeixinPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 微信订单查询.
 * @author yulu 2021/07/09
 * @email l_yu@juling.vip
 */
class QueryOrder extends AbstractAction
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
     * @api {get} /pay/weixin/queryOrder 查询订单
     * @apiDescription 查询订单  对应文档https://doc.yurunsoft.com/PaySDK/98  官方文档：https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_2
     * @apiName queryOrder
     * @apiGroup 微信支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号     订单号和交易流水号二选一
     * @apiParam {string}   transId     交易流水号
     * @apiParam {int}      isApp       是否app订单 默认0:公众号订单    1:app订单
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {string} data.tradeState 支付状态
     * @apiSuccess {int} data.totalFee 应付总金额
     * @apiSuccess {int} data.cashFee 支付现金金额
     * @apiSuccessExample {json}
     * {"code":200000,"message":"请求成功","data":{"tradeState":"SUCCESS","totalFee":1,"cashFee":1}}
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
        $tradeNo = $this->request->input('tradeNo', '');
        $transId = $this->request->input('transId', '');
        if (empty($tradeNo) && empty($transId)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'tradeNo or $transId?');
        }
        $isApp = $this->request->input('isApp', 0);
        $res = $this->weixinPayService->orderQuery($tradeNo, $transId, (int) $isApp);
        if ($res['ok']) {
            return ['tradeState' => $res['tradeState'], 'totalFee' => $res['totalFee'], 'cashFee' => $res['cashFee']];
        }
        throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $res['msg']);
    }
}