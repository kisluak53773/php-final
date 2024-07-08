<?php

declare(strict_types= 1);

namespace App\Middleware\Exception;

class WrongRoleException extends MiddlewareException
{
    protected $message = "Wrong roole";
}