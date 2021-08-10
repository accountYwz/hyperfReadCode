<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Path;

use \Exception;
use Lovetrytry\Jichukuangjia\Match\Dirname;
use Lovetrytry\Jichukuangjia\Base;

/**
 * @author lovetrytry
 * @email lovetrytry@yeah.net
 */
class Composer extends Base
{
    public static function getRoot(): string
    {
        $ref = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);

        $composerDirname = dirname($ref->getFileName());

        if ((new Dirname)->isMatch($composerDirname, "composer") === false) {
            throw new Exception("can't get composer dirname", 500);
        }

        return $composerDirname;
    }
}
