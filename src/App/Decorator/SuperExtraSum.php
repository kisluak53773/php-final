<?php

declare(strict_types= 1);

namespace App\Decorator;

class SuperExtraSum extends SumDecorator
{
    public function sort(int $amount): int
    {
        return $this->sumAction->sort($amount) + 20;
    }
}