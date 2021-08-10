<?php

namespace Lovetrytry\Jichukuangjia\Encryption;

/**
 * 加密方法接口约束
 *
 * @author     lovetrytry@yeah.net
 */
interface EncryptionInterface
{
    public function getPublic($publicName = "");

    public function getPrivate($privateName = "");
}
