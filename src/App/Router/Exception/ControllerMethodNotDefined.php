<?php

declare(strict_types=1);

namespace App\Router\Exception;

class ControllerMethodNotDefined extends RouterException
{
    protected $message = "Such controller method is not defined";
}