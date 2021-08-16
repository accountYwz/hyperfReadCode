<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Di\Definition;

use Hyperf\Config\ProviderConfig;
use Hyperf\Di\Exception\Exception;

class DefinitionSourceFactory
{
    /**
     * @var bool
     */
    protected $enableCache = false;

    /**
     * @var string
     */
    protected $baseUri;

    public function __construct(bool $enableCache = false)
    {
        $this->enableCache = $enableCache;

        if (! defined('BASE_PATH')) {
            throw new Exception('BASE_PATH is not defined.');
        }

        $this->baseUri = BASE_PATH;
    }

    //__invoke()，调用函数的方式调用一个对象时的回应方法
    public function __invoke()
    {
        $configDir = $this->baseUri . '/config';

        $configFromProviders = [];
        //加载composer.lock，把依赖类的配置属性组成一个数组
        if (class_exists(ProviderConfig::class)) {
            $configFromProviders = ProviderConfig::load();
        }
//        var_dump($configFromProviders['dependencies']);
        //解析 /vendor/hyperf/../src/ConfidProvider.php 的dependencies值
        $serverDependencies = $configFromProviders['dependencies'] ?? [];
        if (file_exists($configDir . '/autoload/dependencies.php')) {
            $definitions = include $configDir . '/autoload/dependencies.php';
            $serverDependencies = array_replace($serverDependencies, $definitions ?? []);
        }
//        var_dump($serverDependencies);
        //定义这些资源
        return new DefinitionSource($serverDependencies);
    }
}
