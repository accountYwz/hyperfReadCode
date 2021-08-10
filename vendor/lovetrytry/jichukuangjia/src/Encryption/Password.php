<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Encryption;

use Lovetrytry\Jichukuangjia\Base;

/**
 * @author lovetrytry@yeah.net
 */
class Password extends Base implements EncryptionInterface
{

    public function __construct()
    {
    }

    public function getPublic($publicName = "")
    {
        return "abcd";
    }

    public function getPrivate($privateName = "")
    {
    }
}
