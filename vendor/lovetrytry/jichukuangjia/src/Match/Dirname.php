<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Match;

use Lovetrytry\Jichukuangjia\Base;

/**
 * @author lovetrytry
 * @email lovetrytry@yeah.net
 */
class Dirname extends Base
{
    public static function isMatch($dirname, $match): bool
    {
        $composerDirnameArr = explode(DIRECTORY_SEPARATOR, $dirname);

        if ($match === end($composerDirnameArr)) {
            return true;
        }

        return false;
    }
}
