<?php

namespace App\Framework\JsonFormat;

use App\Constants\CodeMsg;
use \ArrayObject;
use Exception;

class Response
{
    protected $code = 0;
    protected $data;
    protected $message = "";

    public function __construct($data = [], array $codeMsg = [])
    {
        $codeMsg = $codeMsg ?: CodeMsg::SUCCESS;
        $this->code = $codeMsg[0];
        $this->data = is_null($data) ? new ArrayObject : $data;
        $this->message = $codeMsg[1];
    }

    /**
     * [handle description]
     * @date   2021-05-31
     * @throw  \Exception
     * @return [type]     [description]
     */
    public function handle()
    {
        return [
            "code" => $this->code,
            "message" => $this->message,
            "data" => $this->data
        ];
    }

    /**
     * API 请求成功返回
     * @param array $data
     * @return array
     * @author luzhenyu 2021/6/18
     * @email zy_lu@juling.vip
     */
    public static function apiSuccess($data = []): array
    {
        return make(self::class, [$data])->handle();
    }

    /**
     * API 请求错误抛出
     * @param array $codeMsg
     * @return array
     * @author luzhenyu 2021/6/18
     * @email zy_lu@juling.vip
     */
    public static function apiError(array $codeMsg = []): array
    {
        return make(self::class, [[], $codeMsg])->handle();
    }
}
