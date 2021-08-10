<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia;

use Lovetrytry\Jichukuangjia\Exception\RuntimeException;

/**
 * This class describes a base.
 *
 * @author     lovetrytry@yeah.net
 */
class BaseEnum
{
    protected static $enum = [];

    const UNDEFINED = "未定义";

    /**
     * 根据枚举获取对应的值
     *
     * @author     lovetrytry@yeah.net
     *
     * @param      mixed   $code   The code
     * @return     mixed   self::UNDEFINED | static::$enum[$code]
     */
    public static function get($code)
    {
        if (! isset(static::$enum[$code])) {
            return self::UNDEFINED;
        }

        return static::$enum[$code];
    }
}
