<?php

declare (strict_types=1);
namespace App\Service;

use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;
class SqlWriteQueueService
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        $this->__handlePropertyHandler(__CLASS__);
    }
    /**
     * @AsyncQueueMessage
     */
    public function example($params)
    {
        $__function__ = __FUNCTION__;
        $__method__ = __METHOD__;
        return self::__proxyCall(__CLASS__, __FUNCTION__, self::__getParamsMap(__CLASS__, __FUNCTION__, func_get_args()), function ($params) use($__function__, $__method__) {
            // 需要异步执行的代码逻辑
            // 这里的逻辑会在 ConsumerProcess 进程中执行
            var_dump($params);
        });
    }
}