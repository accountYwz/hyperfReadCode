<?php

namespace Lovetrytry\Jichukuangjia;

use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Utils\Context;
use Lovetrytry\Jichukuangjia\Exception\RuntimeException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 针对 Server Request 请求周期中的内容进行获取或处理
 *
 * @author     lovetrytry@yeah.net
 */
class Request
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function __construct()
    {
        $this->request = Context::get(ServerRequestInterface::class);
    }

    public function get($attribute)
    {
        $methodName = __FUNCTION__ . ucfirst($attribute);

        if (method_exists($this, $methodName) === false) {
            return null;
        }

        return call_user_func([$this, $methodName]);
    }

    protected function getCalledAction()
    {
        $callback = $this->getCallback();

        list($class, $action) = explode("@", $callback);

        return compact("class", "action");
    }

    protected function getDispatched($obj = null)
    {
        if (is_null($obj)) {
            return $this->request->getAttribute(Dispatched::class);
        }

        return $obj->getAttribute(Dispatched::class);
    }

    protected function getHandler($obj = null)
    {
        if (is_null($obj)) {
            return $this->getDispatched()->handler;
        }

        return $obj->handler;
    }

    protected function getCallback($obj = null)
    {
        if (is_null($obj)) {
            return $this->getHandler()->callback;
        }

        return $obj->callback;
    }
}
