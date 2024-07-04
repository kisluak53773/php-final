<?php

declare(strict_types= 1);

namespace App\Decorator;

use App\Decorator\Contract\SumInterface;

class StandartSum implements SumInterface
{
    private $tax = 40; 
    
    public function sort(int $amount): int
    {
        return $amount + $this->tax;
    }
}