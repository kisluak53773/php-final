<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Order;
use  \Psr\SimpleCache\InvalidArgumentException;

header('Content-Type: application/json');

class JsonController
{
    public function findByName(): void
    {
        try {
            if (!$_GET['search']) {
                echo json_encode([]);

                exit;
            }

            $cacheService = App::cacheService();

            if ($cacheService->has($_SERVER['REQUEST_URI'])) {
                echo $cacheService->get($_SERVER['REQUEST_URI']);

                exit;
            }

            $page = $_GET['page'] ?? 1;
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            $queryBuilder = App::db()->getEntityManager()->createQueryBuilder();
            $queryBuilder->select('o')
                ->from(Order::class, 'o')
                ->where('o.product LIKE :search')
                ->setParameter('search', '%' . strtolower($_GET['search']) . '%')
                ->setFirstResult($offset)
                ->setMaxResults($perPage);

            $searchResults = $queryBuilder->getQuery()->getArrayResult();

            $cacheService->set($_SERVER['REQUEST_URI'], json_encode($searchResults));

            echo json_encode($searchResults);
        } catch (InvalidArgumentException $e) {
            http_response_code(500);
            echo json_encode(["message" => 'something went wrong']);
        }
    }
}