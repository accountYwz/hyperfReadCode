<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/index', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/test', 'App\Controller\TestController@index');
Router::get('/favicon.ico', function () {
    return '';
});