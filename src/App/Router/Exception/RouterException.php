<?php

declare(strict_types=1);

namespace App\Router\Exception;

use Exception;

class RouterException extends Exception
{
    protected $message = "basic router exception";
}