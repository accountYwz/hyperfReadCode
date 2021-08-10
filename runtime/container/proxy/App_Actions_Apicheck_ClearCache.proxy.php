<?php

declare (strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Actions\Apicheck;

use App\Actions\AbstractAction;
use App\ErrorCode\CodeMsg;
use App\Service\ApiCheckService;
use Hyperf\Di\Annotation\Inject;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
/**
 * 清空验签的缓存，当后台修改或删除appkey时调用
 * @author yulu 2021/07/28
 * @email l_yu@juling.vip
 */
class ClearCache extends AbstractAction
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
     * @var ApiCheckService
     */
    private $apiCheckService;
    /**
     * 
     * @api {get} /apicheck/clearCache 查询订单
     * @apiDescription 清空验签的缓存
     * @apiName clearCache
     * @apiGroup 验签
     * @apiVersion 1.0.0
     * @apiUse V1D0D0
     * @apiParam {string}   appkey     app的key
     * @apiSuccess {object} data 请求内容
     * @apiSuccess {int} data.result  清空成功
     * @apiSuccessExample {json}
     * {"code":200000,"message":"请求成功","data":{"result":1}}
     * @apiError {int} code 请求状态码，非200000
     * @apiError {string} message 请求状态码描述
     * @apiError {array} data 请求内容
     * @apiErrorExample {json} Error-Response:
     *{"code":500000,"message":"服务错误|调用接口错误信息","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"code":400001,"message":"参数错误|key?","data":[]}
     */
    public function handle()
    {
        //校验参数合法性
        $key = $this->request->input('appkey', '');
        if (empty($key)) {
            throw new BusinessException(CodeMsg::PARAM_ERROR, 'appkey?');
        }
        $res = $this->apiCheckService->clearCache($key);
        return ['result' => (int) $res];
    }
}