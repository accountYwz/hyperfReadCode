<?php

declare(strict_types=1);

/**
 * @author Xing Jiapeng
 * @date 2021年03月31日
 */

namespace App\Framework\Key;

use Lovetrytry\Jichukuangjia\Key\RSA;

class Password
{
    const PUBLIC_NAME = "password_rsa_1024_pub.pem";
    const PRIVATE_NAME = "password_rsa_1024.pem";

    static public function getPublic()
    {
        return (new RSA())->getPublic(self::PUBLIC_NAME);
    }

    static public function getPrivate()
    {
        return (new RSA())->getPrivate(self::PRIVATE_NAME);
    }
}
