<?php

declare(strict_types= 1);

namespace App\Middleware\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request): void;
}