<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Order;

header('Content-Type: application/json');

class JsonController
{
    public function findByName(): void
    {
        if (!$_GET['search']) {
            echo json_encode([]);

            exit;
        }

        $cacheService = App::cacheService();

        if ($cacheService->has($_SERVER['REQUEST_URI'])) {
            echo $cacheService->get($_SERVER['REQUEST_URI']);

            exit;
        }

        $queryBuilder = App::db()->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Order::class, 'o')
            ->where('o.product LIKE :search')
            ->setParameter('search', '%' . $_GET['search'] . '%');

        $searchResults = $queryBuilder->getQuery()->getArrayResult();

        $cacheService->set($_SERVER['REQUEST_URI'], json_encode($searchResults));

        echo json_encode($searchResults);
    }
}