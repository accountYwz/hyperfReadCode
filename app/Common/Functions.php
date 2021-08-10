<?php

declare(strict_types=1);

use App\Service\User\UserService;

/**
 * common.php
 * 公共函数，避免功能性函数重复书写
 * 书写规范，必须使用function_exists()方法判断
 */

if (!function_exists('encryptPassword')) {
    /**
     * encryptPassword
     * 加密密码
     * @param string $password 用户输入的密码
     * @return string
     */
    function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('checkPassword')) {
    /**
     * checkPassword
     * 检测密码
     * User：YM
     * Date：2020/1/10
     * Time：下午12:48.
     * @param $value
     * @param $hashedValue
     * @return bool
     */
    function checkPassword($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
}

if (!function_exists('getFileCdnUrl')) {
    /**
     * @param $path
     * @return string
     * @note 得到拼接好的cdn地址
     * @author   fengpengyuan   2021/7/14
     * @email  py_feng@juling.vip
     * @modifier fengpengyuan 2021/7/14
     */
    function getFileCdnUrl($path)
    {
        return env('COS_CDN') . $path;
    }
}

if (!function_exists('getUid')) {
    /**
     * @func 获取用户ID
     * @author luzhenyu 2021/8/2
     * @email zy_lu@juling.vip
     * @modifier luzhenyu 2021/8/2
     */
    function getUid()
    {
        $userService = make(UserService::class);
        return $userService->getUid();
    }
}
