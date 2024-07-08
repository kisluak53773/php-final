<?php

declare(strict_types=1);

namespace App\Middleware\Exception;

use Exception;

class MiddlewareException extends Exception
{
    protected $message = "basic middleware exception";
}