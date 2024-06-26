<?php

declare(strict_types=1);

namespace App\Router\Exception;

class ControllerNotFound extends RouterException
{
    protected $message = "Such controller does not exist";
}