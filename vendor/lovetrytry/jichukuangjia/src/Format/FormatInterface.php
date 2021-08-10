<?php

namespace Lovetrytry\Jichukuangjia\Format;

use Psr\Http\Message\StreamInterface;

/**
 * This interface describes a format interface.
 *
 * @author     lovetrytry@yeah.net
 */
interface FormatInterface
{
    public function toArray(): array;

    public function toStream(): StreamInterface;
}