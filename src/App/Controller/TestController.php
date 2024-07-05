<?php

declare(strict_types= 1);

namespace App\Controller;

use App\Service\Contracts\ContainerTestInterface;
use React\EventLoop\Loop;

class TestController
{
    public function __construct(private ContainerTestInterface $containerTest)
    {}

    public function test(): void
    {
        echo $this->containerTest->getData();
    }

    public function timer(): void
    {
        $loop = Loop::get();

        $loop->addTimer(1, function () {
            echo 'Hello' . PHP_EOL;
        });
        
        echo "World";

        $loop->run();
    } 
}