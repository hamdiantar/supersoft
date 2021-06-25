<?php

namespace App\Traits;

use Exception;

trait LoggerError
{
    function logErrors(Exception $exception)
    {
        logger([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }
}
