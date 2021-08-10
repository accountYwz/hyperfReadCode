<?php

declare (strict_types=1);
namespace App\Actions\Pay\Weixin;

use App\Actions\AbstractAction;
use App\Constants\CodeMsg;
use App\Service\Pay\WeixinPayService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 创建公众号订单.
 * @author yulu 2021/07/09
 * @email l_yu@juling.vip
 */
class CreateGzhOrder extends AbstractAction
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
     * @api {post} /pay/weixin/createGzhOrder 创建公众号订单
     * @apiDescription 创建公众号订单  对应文档https://doc.yurunsoft.com/PaySDK/77  官方文档：https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     * @apiName createGzhOrder
     * @apiGroup 微信支付
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   tradeNo     订单号
     * @apiParam {string}   goodsInfo   商品描述
     * @apiParam {int}      totalFee    订单金额
     * @apiParam {string}   clientIP    客户端IP
     * @apiParam {string}   openId      用户openId
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {int} data.prepayId 预支付交易会话标识
     * @apiSuccessExample {json}
     * {"code":200000,"message":"请求成功","data":{"prepayId":"wx201410272009395522657a690389285100"}}
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
        $clientIP = $this->request->input('clientIP');
        if (empty($clientIP)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'clientIP?');
        }
        $openId = $this->request->input('openId');
        if (empty($openId)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'openId?');
        }
        $res = $this->weixinPayService->creatGzhOrder($tradeNo, $goodsInfo, $totalFee, $clientIP, $openId);
        if ($res['ok']) {
            return ['prepayId' => $res['prepayId']];
        }
        throw new BusinessException(CodeMsg::UNKNOWN_ERROR, $res['msg']);
    }
}