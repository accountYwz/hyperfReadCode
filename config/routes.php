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
        // key
        Router::addRoute(['GET', 'HEAD'], 'key/password/public', 'App\Actions\Key\Password\PublicContent@handle');

        //apicheck
        Router::addRoute(['GET'], 'apicheck/clearCache', 'App\Actions\Apicheck\Apicheck\ClearCache@handle');
        //微信支付
        Router::addRoute(['POST'], 'pay/weixin/createGzhOrder', 'App\Actions\Pay\Weixin\CreateGzhOrder@handle');
        Router::addRoute(['POST'], 'pay/weixin/createAppOrder', 'App\Actions\Pay\Weixin\CreateAppOrder@handle');
        Router::addRoute(['GET'], 'pay/weixin/queryOrder', 'App\Actions\Pay\Weixin\QueryOrder@handle');
        Router::addRoute(['POST'], 'pay/weixin/payToUser', 'App\Actions\Pay\Weixin\PaytoUser@handle');
        Router::addRoute(['POST'], 'pay/weixin/createPayUrl', 'App\Actions\Pay\Weixin\CreatePayUrl@handle');
        //支付宝支付
        Router::addRoute(['POST'], 'pay/alipay/createAppOrder', 'App\Actions\Pay\Alipay\CreateAppOrder@handle');
        Router::addRoute(['POST'], 'pay/alipay/createPayUrl', 'App\Actions\Pay\Alipay\CreatePayUrl@handle');
        Router::addRoute(['POST'], 'pay/alipay/payToUser', 'App\Actions\Pay\Alipay\PaytoUser@handle');
        Router::addRoute(['GET'], 'pay/alipay/queryOrder', 'App\Actions\Pay\Alipay\QueryOrder@handle');
        //app配置
        Router::addRoute(['GET'], 'app/config/android', 'App\Actions\App\Config\Android@handle');
        Router::addRoute(['GET'], 'app/config/ios', 'App\Actions\App\Config\Ios@handle');
        //获取系统时间
        Router::addRoute(['GET'], 'app/system/getSystemInfo', 'App\Actions\App\System\GetSystemInfo@handle');
        //login
        Router::addRoute(['GET', 'POST'], 'account/tel/login', 'App\Actions\Account\Tel\Login@handle');
        Router::addRoute(['GET', 'POST'], 'account/Wangyi/login', 'App\Actions\Account\Wangyi\Login@handle');

        Router::addRoute(['GET', 'POST'], 'notice/sms/sendSms', 'App\Actions\Notice\Sms\SendSms@handle');
        Router::addRoute(['GET', 'POST'], 'notice/sms/checkCode', 'App\Actions\Notice\Sms\CheckCode@handle');

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
        Router::addRoute(['GET'], 'account/weixin/info', 'App\Actions\Account\Weixin\Info@handle');
        //app举报
        Router::addRoute(['POST'], 'app/report/add', 'App\Actions\App\Report\Add@handle');
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
