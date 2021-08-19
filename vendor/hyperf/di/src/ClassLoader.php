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
namespace Hyperf\Di;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Dotenv\Dotenv;
use Dotenv\Repository\Adapter;
use Dotenv\Repository\RepositoryBuilder;
use Hyperf\Di\Annotation\ScanConfig;
use Hyperf\Di\Annotation\Scanner;
use Hyperf\Di\Aop\ProxyManager;
use Hyperf\Di\LazyLoader\LazyLoader;
use Hyperf\Utils\Composer;

class ClassLoader
{
    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected $composerClassLoader;

    /**
     * The container to collect all the classes that would be proxy.
     * [ OriginalClassName => ProxyFileAbsolutePath ].
     *
     * @var array
     */
    protected $proxies = [];

    public function __construct(ComposerClassLoader $classLoader, string $proxyFileDir, string $configDir)
    {
        //将ComposerClassLoader $classLoader加载器复制给hyperf的加载器
        $this->setComposerClassLoader($classLoader);
        //解析.env环境
        if (file_exists(BASE_PATH . '/.env')) {
            $this->loadDotenv();
        }
        //生成代理类缓存，代理类缓存有啥作用？生成代理缓存，以便aop切面
        // Scan by ScanConfig to generate the reflection class map
        //实例化配置扫码配置
        $config = ScanConfig::instance($configDir);
        $classLoader->addClassMap($config->getClassMap());
        //创建扫描器
        $scanner = new Scanner($this, $config);
        //扫描形成映射类
        $reflectionClassMap = $scanner->scan();
        // Get the class map of Composer loader
        $composerLoaderClassMap = $this->getComposerClassLoader()->getClassMap();
        $proxyManager = new ProxyManager($reflectionClassMap, $composerLoaderClassMap, $proxyFileDir);
        $this->proxies = $proxyManager->getProxies();
    }

    public function loadClass(string $class): void
    {
        $path = $this->locateFile($class);

        if ($path) {
            include $path;
        }
    }

    public static function init(?string $proxyFileDirPath = null, ?string $configDir = null): void
    {
        if (! $proxyFileDirPath) {
            // This dir is the default proxy file dir path of Hyperf
            $proxyFileDirPath = BASE_PATH . '/runtime/container/proxy/';
        }

        if (! $configDir) {
            // This dir is the default proxy file dir path of Hyperf
            $configDir = BASE_PATH . '/config/';
        }
        //返回所有已注册的 __autoload() 函数.composer工作时已经注册
        $loaders = spl_autoload_functions();

        // Proxy the composer class loader
        foreach ($loaders as &$loader) {
            $unregisterLoader = $loader;

            if (is_array($loader) && $loader[0] instanceof ComposerClassLoader) {
                /** @var ComposerClassLoader $composerClassLoader */
                $composerClassLoader = $loader[0];
                //把composer的所有类向注解类AnnotationRegistry注册
                AnnotationRegistry::registerLoader(function ($class) use ($composerClassLoader) {
                    return (bool) $composerClassLoader->findFile($class);
                });
                //初始化DI加载器
                $loader[0] = new static($composerClassLoader, $proxyFileDirPath, $configDir);
            }
            spl_autoload_unregister($unregisterLoader);
        }

        unset($loader);

        //为啥又注册一遍？
        // Re-register the loaders
        foreach ($loaders as $loader) {
            spl_autoload_register($loader);
        }
        //初始化懒加载器。 这会将 LazyLoader 添加到自动加载队列的顶部
        // Initialize Lazy Loader. This will prepend LazyLoader to the top of autoload queue.
        LazyLoader::bootstrap($configDir);
    }

    public function setComposerClassLoader(ComposerClassLoader $classLoader): self
    {
        $this->composerClassLoader = $classLoader;
        // Set the ClassLoader to Hyperf\Utils\Composer to avoid unnecessary find process.
        //将 ClassLoader 设置为 Hyperf\Utils\Composer 以避免不必要的查找过程
        Composer::setLoader($classLoader);
        return $this;
    }

    public function getComposerClassLoader(): ComposerClassLoader
    {
        return $this->composerClassLoader;
    }

    protected function locateFile(string $className): ?string
    {
        if (isset($this->proxies[$className]) && file_exists($this->proxies[$className])) {
            $file = $this->proxies[$className];
        } else {
            $file = $this->getComposerClassLoader()->findFile($className);
        }

        return is_string($file) ? $file : null;
    }

    protected function loadDotenv(): void
    {
        $repository = RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(Adapter\PutenvAdapter::class)
            ->immutable()
            ->make();

        Dotenv::create($repository, [BASE_PATH])->load();
    }
}
