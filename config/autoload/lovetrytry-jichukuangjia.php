<?php

declare(strict_types=1);
/**
 * 基础框架配置文件
 *
 * @author lovetrytry@yeah.net
 */

use Lovetrytry\Jichukuangjia\Format\ClassEnum;

return [
    // 本配置中所有文件默认目录，留空或只填 DIRECTORY_SEPARATOR 则为项目根目录
    "baseDir" => DIRECTORY_SEPARATOR . 'storage',
    "password" => [
        "rsa" => [
            // 读取时会拼接上 $baseDir，🌰：项目根目录/storage/keys/password_rsa_1024_pub.pem
            "public" => DIRECTORY_SEPARATOR . 'keys' . DIRECTORY_SEPARATOR . 'password_rsa_1024_pub.pem',
            "private" => DIRECTORY_SEPARATOR . 'keys' . DIRECTORY_SEPARATOR . 'password_rsa_1024.pem',
        ]
    ],
    "format" => [
        // 响应格式化类枚举
        "default" => ClassEnum::CMD,
        // 接口响应的结构
        "keys" => [
            "code" => "code",
            "msg" => "message",
            "data" => "data",
        ],
        // true 校验接口响应数据结构，false 不校验
        "apiResponseCheck" => env('API_RESPONSE_CHECK', true),
    ],
    "exception" => [
        // 错误码类
        "ErrorCode" => \Lovetrytry\Jichukuangjia\Constants\ErrorCode::class,
    ],
    "mock" => [
        "doc" => [
            // 使用正则方法 preg_match 匹配文档块
            "pattern" => "/\@apiSuccessExample \{json\}([\S\s]+?)\* \@[a-zA-Z]+?/",
            // 使用 preg_replace 去掉多余字符，使文档块成为一行字符串，可能也是一行正确的 json 字符串
            "replace2jsonString" => "/\/\/ .*|(\s+\*)/",
        ]
    ]
];
