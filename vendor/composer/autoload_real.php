<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInita640a45dad785fd23bd8fd6894469153
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }
        //PHP版本、运行环境检测
        require __DIR__ . '/platform_check.php';

        //spl_autoload_register将函数注册到SPL __autoload函数栈中。如果该栈中的函数尚未激活，则激活它们。
        //注册一个自动加载器loadClassLoader,如果在调用的代码中引入类不存在，则调用该加载器查找。
        spl_autoload_register(array('ComposerAutoloaderInita640a45dad785fd23bd8fd6894469153', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(\dirname(__FILE__)));


        //从 autoload 自动装载函数队列中移除指定的函数。
        spl_autoload_unregister(array('ComposerAutoloaderInita640a45dad785fd23bd8fd6894469153', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require __DIR__ . '/autoload_static.php';
            // 设置 $loader 实例的 prefixLengthsPsr4、prefixDirsPsr4、prefixesPsr0、classMap 属性
            call_user_func(\Composer\Autoload\ComposerStaticInita640a45dad785fd23bd8fd6894469153::getInitializer($loader));
        } else {
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }
        // 完成 Composer 包管理器类自动加载注册
        $loader->register(true);
        // 在 项目中，除了类之外，还支持不归属于任何类的辅助函数，
        //这些辅助函数通常定义在 helpers.php 文件中，Composer 通用支持对这类文件的自动加载，这一块的处理通样针对是否支持静态初始化进行了区分
        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInita640a45dad785fd23bd8fd6894469153::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        // 最后遍历这些文件逐个引入，并将它们的标识符存放到全局变量 __composer_autoload_files 中。
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequirea640a45dad785fd23bd8fd6894469153($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequirea640a45dad785fd23bd8fd6894469153($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}
