<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Order;
use App\Entity\User;
use App\Service\EntityService;

header('Content-Type: application/json');

class JsonController
{
    public function findByName(): void
    {
        if (!$_GET['search']) {
            echo json_encode([]);

            exit;
        }

        $queryBuilder = App::db()->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Order::class, 'o')
            ->where('o.product LIKE :search')
            ->setParameter('search', '%' . $_GET['search'] . '%');

        $searchResults = $queryBuilder->getQuery()->getArrayResult();

        echo json_encode($searchResults);
    }
}