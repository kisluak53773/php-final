<?php

declare(strict_types= 1);

namespace App\Service\Contracts;

use Doctrine\Common\Collections\Collection;

interface SortStrategyInterface
{
    public function sort(Collection $collection): Collection;
}