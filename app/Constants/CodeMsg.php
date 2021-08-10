<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\Annotation\Constants;
use Lovetrytry\Jichukuangjia\Constants\ErrorCode;

/**
 * 错误码描述.
 * @Constants
 * @method static getMessage(int $codeMsg)
 */
class CodeMsg extends ErrorCode
{
    /**
     * @Message("请求成功")
     */
    const SUCCESS = 200000;

    /**
     * @Message("未知错误")
     */
    const UNKNOWN_ERROR = 500000;

    /**
     * @Message("参数错误")
     */
    const PARAM_ERROR = 400001;

    /**
     * @Message("token错误")
     */
    const TOKEN_ERROE = 401002;

    /**
     * @Message("账号已注销")
     */
    const LOGOFF = 401003;

    /**
     * @Message("账号已冻结")
     */
    const ACCOUNT_DISABLE = 401005;

    /**
     * @Message("缺少验签参数")
     */
    const APICHECK_PARAM_LOST_ERROE = 403001;

    /**
     * @Message("请求已过期")
     */
    const APICHECK_EXPIRE_ERROE = 403002;

    /**
     * @Message("appkey错误")
     */
    const APICHECK_APPKEY_ERROE = 403003;

    /**
     * @Message("验签错误")
     */
    const APICHECK_SIGN_ERROE = 403004;

    /**
     * @Message("不能重复请求")
     */
    const APICHECK_REPEAT_ERROE = 403008;

    /**
     * @Message("请求资源未找到.")
     */
    const RESOURCE_NOT_FOUND = 404000;
}
