<?php

declare(strict_types=1);

namespace Lovetrytry\Jichukuangjia;

use Lovetrytry\Jichukuangjia\Config;
use Lovetrytry\Jichukuangjia\Format\ClassEnum;
use Lovetrytry\Jichukuangjia\Format\FormatInterface;

class Format
{
    /**
     * @var Config
     */
    protected $config;

    protected $class;

    public function __construct()
    {
        $this->config = new Config;

        $this->class = $this->getClass();
    }

    public function __call($name, $arguments)
    {
        return $this->class->{$name}(...$arguments);
    }

    public function getContent($content)
    {
        $deContent = json_decode($content, true);

        if (is_null($deContent)) {
            return $content;
        }

        return $deContent;
    }

    protected function getClass()
    {
        $className = $this->getClassName();

        if (is_null($className)) {
            return null;
        }

        $class = new $className(0);

        if ($class instanceof FormatInterface) {
            return $class;
        }

        return null;
    }

    protected function getClassName()
    {
        $default = $this->config->get("lovetrytry-jichukuangjia.format.default");

        $className = ClassEnum::get($default);

        if (class_exists($className)) {
            return $className;
        }

        return null;
    }
}
