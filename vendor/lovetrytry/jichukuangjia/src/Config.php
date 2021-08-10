<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;

/**
 * This class describes a base.
 *
 * @author     lovetrytry@yeah.net
 */
class Config
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct()
    {
        $container = ApplicationContext::getContainer();
        $this->config = $container->get(ConfigInterface::class);
    }

    public function __call($name, $arguments)
    {
        return $this->config->{$name}(...$arguments);
    }
}
