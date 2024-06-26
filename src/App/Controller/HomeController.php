<?php

declare(strict_types=1);

namespace App\Controller;

header("Content-Type: text/html; charset=UTF-8");

class HomeController
{
    public function home(): void
    {
        echo "Hello World!";
    }

    public function about(): void
    {
        xdebug_info();
    }
}