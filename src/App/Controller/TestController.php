<?php

declare(strict_types= 1);

namespace App\Controller;

use App\Service\Contracts\ContainerTestInterface;

class TestController
{
    public function __construct(private ContainerTestInterface $containerTest)
    {}

    public function test(): void
    {
        echo $this->containerTest->getData();
    }
}