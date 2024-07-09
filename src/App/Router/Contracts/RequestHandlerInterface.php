<?php

declare(strict_types= 1);

namespace App\Router\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request , ResponseInterface $response): ResponseInterface;
}