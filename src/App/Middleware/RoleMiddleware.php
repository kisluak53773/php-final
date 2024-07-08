<?php

declare(strict_types= 1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use App\Middleware\Contracts\MiddlewareInterface;

class RoleMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
        {
            echo 'You are not admin';

            exit;
        }
        return;
    }
}