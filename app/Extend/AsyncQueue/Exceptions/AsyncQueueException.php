<?php

declare(strict_types=1);

namespace App\Extend\AsyncQueue\Exceptions;

use Throwable;

class AsyncQueueException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
