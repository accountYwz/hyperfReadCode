<?php

namespace Lovetrytry\Jichukuangjia\Format;

use \ArrayObject;
use Lovetrytry\Jichukuangjia\BaseEnum;
use Lovetrytry\Jichukuangjia\Exception\RuntimeException;

class ClassEnum extends BaseEnum
{
    const CMD = 1;

    protected static $enum = [
        self::CMD => __NAMESPACE__ . '\CMD',
    ];
}
