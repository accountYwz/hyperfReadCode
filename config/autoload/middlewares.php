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
return [
    'http' => [
//        \App\Middleware\Request::class,
        //验签
        \App\Middleware\Sign::class,

        \App\Middleware\Request::class,
        \App\Middleware\Response::class,

        // last
        \Lovetrytry\Jichukuangjia\Middleware\Response::class,
    ],
];
