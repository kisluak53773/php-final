<?php

declare(strict_types= 1);

namespace App\Decorator\Contract;

interface SumInterface
{
    public function sort(int $amount): int;
}