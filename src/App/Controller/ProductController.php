<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Product;
use DateTime;
use  \Psr\SimpleCache\InvalidArgumentException;

header('Content-Type: application/json');

class ProductController
{
    public function createProduct(): void
    {
        $entityManager = App::db()->getEntityManager();

        $product = new Product();

        $product->setName($_POST['name']);
        $product->setPrice($_POST['price']);
        $product->setCreatedAt(new DateTime('now'));
        $product->setUpdatedAt(new DateTime('now'));

        $entityManager->persist($product);

        $entityManager->flush();

        http_response_code(201);
        echo json_encode(['message' => 'Product created']);
    }

    public function getProducts(): void
    {
        try {
            $search = $_GET['search'] ?? '';
            $page = $_GET['page'] ?? 1;
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            $queryBuilder = App::db()->getEntityManager()->createQueryBuilder();
            $queryBuilder->select('p')
                ->from(Product::class, 'p')
                ->where('p.name LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%')
                ->setFirstResult((int)$offset)
                ->setMaxResults($perPage);

            $searchResults = $queryBuilder->getQuery()->getArrayResult();

            echo json_encode($searchResults);
        } catch (InvalidArgumentException $e) {
            http_response_code(500);
            echo json_encode(["message" => 'something went wrong']);
        }
    }
}