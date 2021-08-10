<?php

namespace Lovetrytry\Jichukuangjia\Format;

use \ArrayObject;
use \Exception;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Lovetrytry\Jichukuangjia\Config;
use Lovetrytry\Jichukuangjia\Exception\BusinessException;
use Lovetrytry\Jichukuangjia\Validation\InterfaceResponse;
use Psr\Http\Message\StreamInterface;

class CMD implements FormatInterface
{
    protected $codeKey = "code";
    protected $msgKey = "msg";
    protected $dataKey = "data";

    protected $code = 0;
    protected $msg = "";
    protected $data;

    /**
     * @var Config
     */
    protected $config;

    public function __construct($code, $data = null, string $msg = "")
    {

        $this->config = new Config;

        $this->setCodeKey();
        $this->setMsgKey();
        $this->setDataKey();

        $this->setCode($code);
        $this->setData($data);
        $this->setMsg($msg);
    }

    public function setCode($code)
    {
        if ($code === 0) {
            $code = 200000;
        } elseif ($code === 1) {
            $code = 500000;
        }

        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setMsg(string $msg)
    {
        $this->msg = $msg;

        return $this;
    }

    public function setData($data = null)
    {
        $this->data = $data;

        $this->handleData();

        return $this;
    }

    /**
     * @return [type]     [description]
     */
    public function toArray(): array
    {
        return [
            $this->codeKey => $this->code,
            $this->msgKey => $this->msg,
            $this->dataKey => $this->data
        ];
    }

    public function toStream(): StreamInterface
    {
        return new SwooleStream((String) $this);
    }

    public function toString(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return (string) json_encode($this->toArray());
    }

    protected function setCodeKey()
    {
        $this->setKey("code");
    }

    protected function setMsgKey()
    {
        $this->setKey("msg");
    }

    protected function setDataKey()
    {
        $this->setKey("data");
    }

    protected function setKey($key)
    {
        $value = $this->config->get("lovetrytry-jichukuangjia.format.keys.{$key}");

        if (! empty($value)) {
            $vKey = $key . 'Key';

            $this->$vKey = $value;
        }
    }

    protected function handleData()
    {
        $interfaceResponse = new InterfaceResponse($this->toArray());

        if (is_null($this->data)) {
            $this->data = $interfaceResponse->getDefaultData($this->dataKey);

            return;
        }

        if ($this->config->get("lovetrytry-jichukuangjia.format.apiResponseCheck")) {
            $interfaceResponseCheck = $interfaceResponse->handle();

            if (! $interfaceResponseCheck) {
                throw new BusinessException(500, "接口响应结构与文档不符，请检查。");
            }
        }
    }
}
