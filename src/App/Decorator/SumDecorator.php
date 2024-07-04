<?php

declare(strict_types= 1);

namespace App\Decorator;

use App\Decorator\Contract\SumInterface;

abstract class SumDecorator implements SumInterface
{
    public function __construct(protected SumInterface $sumAction)
    {}
}