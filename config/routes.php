<?php

declare(strict_types=1);

use App\Actions\AboutUs\AboutUs\AboutUs;
use App\Middleware\Redirect\Logged;
use App\Middleware\Redirect\UnLogged;
use Hyperf\HttpServer\Router\Router;

// 未登录状态下可访问的路由
Router::addGroup(
    '/',
    function () {

        Router::addRoute(['GET', "POST"], 'notice/test/test', 'App\Actions\Notice\Test\Test@handle');
},

    [
        'middleware' => [
            UnLogged::class,
        ],
    ]
);

// 登录状态下可访问的路由
Router::addGroup(
    '/',
    function () {
    },
    [
        'middleware' => [
            Logged::class,
        ],
    ]
);

Router::get('/favicon.ico', function () {
    return '';
});
