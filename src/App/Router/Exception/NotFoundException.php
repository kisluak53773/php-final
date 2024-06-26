<?php

declare(strict_types=1);

namespace App\Router\Exception;

class NotFoundException extends RouterException
{
    protected $message = "404 Not Found";
}