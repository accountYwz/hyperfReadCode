<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Path;

use \Exception;
use Lovetrytry\Jichukuangjia\Base;
use Lovetrytry\Jichukuangjia\Path\Vendor;

/**
 * @author lovetrytry
 * @email lovetrytry@yeah.net
 */
class Project extends Base
{
    public static function getRoot(): string
    {
        $vendorDirname = (new Vendor)->getRoot();

        return dirname($vendorDirname);
    }

    public static function getStorage()
    {
        return self::getRoot() . DIRECTORY_SEPARATOR . 'storage';
    }
}
