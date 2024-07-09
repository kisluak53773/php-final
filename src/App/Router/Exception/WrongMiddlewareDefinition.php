<?php

declare(strict_types= 1);

namespace App\Router\Exception;

class WrongMiddlewareDefinition extends RouterException
{
    protected $message = 'Wrong middleware definition';
}