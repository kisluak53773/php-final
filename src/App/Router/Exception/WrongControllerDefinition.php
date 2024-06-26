<?php

declare(strict_types=1);

namespace App\Router\Exception;

class WrongControllerDefinition extends RouterException
{
    protected $message = 'Wrong controller definition';
}