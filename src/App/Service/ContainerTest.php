<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Contracts\ContainerTestInterface;

class ContainerTest implements ContainerTestInterface
{
    public function getData():int
    {
        return 10;
    }
}