<?php

declare(strict_types=1);

namespace App\Extend\AsyncQueue\Exceptions;

use Throwable;

class JobPushFailException extends AsyncQueueException
{
    public function __construct($message = 'async queue job push fail.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}