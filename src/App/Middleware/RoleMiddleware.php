<?php

declare(strict_types= 1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use App\Middleware\Contracts\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use App\Router\Wrapper\Response;
use App\Router\Contracts\RequestHandlerInterface;

class RoleMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
        {
            $handler->handle($request, new Response('You are not admin'));

            exit;
        }
        return new Response('ok');
    }
}