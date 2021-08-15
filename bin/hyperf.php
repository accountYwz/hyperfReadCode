#!/usr/bin/env php
<?php

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');

error_reporting(E_ALL);

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

require BASE_PATH . '/vendor/autoload.php';

// Self-called anonymous function that creates its own scope and keep the global namespace clean.
(function () {
    Hyperf\Di\ClassLoader::init();
    /** @var Psr\Container\ContainerInterface $container */
    $container = require BASE_PATH . '/config/container.php';

//    $res = $container->get(\Hyperf\Cache\Cache::class);
//    var_dump($res);
//    $container->get()会遍历初并始化Hyperf\Contract\ApplicationInterface::class Entry所有的类。resolver
    $application = $container->get(Hyperf\Contract\ApplicationInterface::class);
    //Symfony\Component\Console\Command\Application->run()
    //运行当前应用
    $application->run();
})();
