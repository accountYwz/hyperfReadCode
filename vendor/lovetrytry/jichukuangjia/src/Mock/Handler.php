<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Mock;

use \Exception;
use Lovetrytry\Jichukuangjia\Config;
use ReflectionClass;
use ReflectionMethod;

class Handler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Action 所属类，含完整命名空间
     * @var String
     */
    protected $class;

    /**
     * Action 的名称
     * @var String
     */
    protected $action;

    /**
     * @var ReflectionClass@getMethods
     */
    protected $reflectionMethods;

    public function __construct(string $class, string $action)
    {
        $this->class = $class;

        $this->action = $action;

        $reflection = new ReflectionClass($this->class);

        $this->reflectionMethods = $reflection->getMethods(
            ReflectionMethod::IS_PUBLIC
            + ReflectionMethod::IS_PROTECTED
            + ReflectionMethod::IS_PRIVATE
        );

        $this->config = new Config;
    }

    public function getMockData()
    {
        foreach ($this->reflectionMethods as $f) {
            if ($f->name == $this->action) {
                $doc = $f->getDocComment();
                break;
            }
        }

        preg_match(
            $this->config->get("lovetrytry-jichukuangjia.mock.doc.pattern"),
            $doc,
            $matchs
        );

        $str = preg_replace(
            $this->config->get("lovetrytry-jichukuangjia.mock.doc.replace2jsonString"),
            "",
            $matchs[1]
        );

        $arr = json_decode($str, true);

        if ($arr === null) {
            throw new Exception("无法解析接口文档，请检查 `{$this->class}@{$this->action}` 文档是否正确。\r\n", 500);
        }

        return $arr;
    }
}
