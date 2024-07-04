<?php

declare(strict_types= 1); 

namespace App\Service;

use App\Service\Contracts\SortStrategyInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

class DescendingSortStrategy implements SortStrategyInterface
{
    public function sort(Collection $collection): Collection
    {
        $criteria = Criteria::create()->orderBy(['createdAt' => 'DESC']);
        $arrayCollection = new ArrayCollection($collection->getValues());
        $sortedData = $arrayCollection->matching($criteria);

        return $sortedData;
    }
}