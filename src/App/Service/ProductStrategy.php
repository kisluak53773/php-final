<?php

declare(strict_types= 1);

namespace App\Service;

use App\Service\Contracts\SortStrategyInterface;
use Doctrine\Common\Collections\Collection;

class ProductStrategy
{

    public function __construct(private SortStrategyInterface $cartSortStrategy){}

    public function attachSortStrategy(SortStrategyInterface $sortStrategy): void
    {
        $this->cartSortStrategy = $sortStrategy;
    }

    public function sort(Collection $products): Collection
    {
        return $this->cartSortStrategy->sort($products);
    }
}