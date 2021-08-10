<?php

declare(strict_types=1);

namespace App\Extend\AsyncQueue\Exceptions;

use Throwable;

class HostNotFoundException extends AsyncQueueException
{
    public function __construct($message = 'async queue host was not found.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}