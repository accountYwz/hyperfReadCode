<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Path;

use \Exception;
use Lovetrytry\Jichukuangjia\Base;
use Lovetrytry\Jichukuangjia\Path\Composer;
use Lovetrytry\Jichukuangjia\Match\Dirname;

/**
 * @author lovetrytry
 * @email lovetrytry@yeah.net
 */
class Vendor extends Base
{
    public static function getRoot(): string
    {
        $composerDirname = (new Composer)->getRoot();

        $vendorDirname = dirname($composerDirname);

        if ((new Dirname)->isMatch($vendorDirname, "vendor") === false) {
            throw new Exception("can't get vendor dirname", 500);
        }

        return $vendorDirname;
    }
}
