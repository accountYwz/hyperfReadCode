<?php

declare(strict_types=1);

namespace Lovetrytry\Jichukuangjia;

use Lovetrytry\Jichukuangjia\Exception\RuntimeException;
use Lovetrytry\Jichukuangjia\Format;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * 处理响应的内容
 *
 * @author     lovetrytry@yeah.net
 */
class Response// implements PsrResponseInterface
{
    /**
     * @var null|PsrResponseInterface
     */
    protected $response;

    public function __construct(?PsrResponseInterface $response = null)
    {
        $this->response = $response;
    }

    public function handle(): PsrResponseInterface
    {
        $format = new Format;

        $content = $format->getContent($this->response->getBody()->getContents());

        if ($this->response->isOk()) {
            $format->setData($content);
        } else {
            if (isset($content["code"]) && isset($content["msg"])) {
                $format->setCode($content["code"]);
                $format->setMsg($content["msg"]);
            } else {
                $format->setCode($this->response->getStatusCode());
            }
        }

        return $this->response->withBody($format->toStream());
    }

    public function __call($name, $arguments)
    {
        $response = $this->getResponse();

        if (! method_exists($response, $name)) {
            throw new RuntimeException(sprintf('Call to undefined method %s::%s()', get_class($this), $name));
        }

        return $response->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $response = Context::get(PsrResponseInterface::class);

        if (! method_exists($response, $name)) {
            throw new RuntimeException(sprintf('Call to undefined static method %s::%s()', self::class, $name));
        }

        return $response::{$name}(...$arguments);
    }

    protected function getResponse()
    {
        if ($this->response instanceof PsrResponseInterface) {
            return $this->response;
        }

        return Context::get(PsrResponseInterface::class);
    }
}
