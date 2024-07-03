<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Product;
use DateTime;
use App\Service\EntityService;

header('Content-Type: application/json');

class CartController
{
    public function createCart(): void
    {
        $entityManager = App::db()->getEntityManager();
        $userId = 1;
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'user not found']);

            return;
        }

        $cart = new Cart();
        $cart->setUser($user);
        $cart->setCreatedAt(new DateTime('now'));
        $cart->setUpdatedAt(new DateTime('now'));

        $user->setCart($cart);

        $entityManager->persist($cart);
        $entityManager->persist($user);
        $entityManager->flush();

        http_response_code(201);
        echo json_encode(['message'=> 'cart created']);
    }

    public function addToCart(): void
    {
        if (!$_POST['productId']) {
            http_response_code(400);
            echo json_encode(['message'=> $_POST]);

            return;
        }

        $entityManager = App::db()->getEntityManager();

        $product = $entityManager->getRepository(Product::class)->find($_POST['productId']);
        $cart = $entityManager->getRepository(Cart::class)->find($_SESSION['cartId']);

        if (!$product || !$cart) {
            http_response_code(404);
            echo json_encode(['message'=> 'cart or product does not exist']);

            return;
        }

        $cart->addProduct($product);

        $entityManager->persist($cart);
        $entityManager->flush();

        http_response_code(200);
        echo json_encode(['message'=> 'product successfully added']);
    }

    public function getProductsInCart(): void
    {
        $entityManager = App::db()->getEntityManager();

        $cart = $entityManager->getRepository(Cart::class)->find($_SESSION['cartId']);

        $productCollection = $cart->getProducts()->toArray() ?? [];

        $products = [];

        if ($productCollection) {
            foreach ($productCollection as $product) {
                $convertedProduct = EntityService::convertProductIntoArray($product);
                array_push($products, $convertedProduct);
            }
        }

        http_response_code(200);
        echo json_encode($products);
    }

    public function deleteProduct(string $id): void
    {
        $entityManager = App::db()->getEntityManager();
        $cart = $entityManager->getRepository(Cart::class)->find($_SESSION['cartId']);

        if (!$cart) {
            http_response_code(404);
            echo json_encode(['message'=> 'can not find your cart']);

            return;
        }

        $productToRemove = $entityManager->getRepository(Product::class)->find($id);

        if (!$productToRemove) {
            http_response_code(404);
            echo json_encode(['message'=> 'no such product']);

            return;
        }

        if ($cart->getProducts()->contains($productToRemove)) {
            $cart->getProducts()->removeElement($productToRemove);
            $entityManager->persist($cart);
            $entityManager->flush();

            http_response_code(200);
            echo json_encode(['message'=> 'product deleted']);

            return;
        }

        http_response_code(404);
        echo json_encode(['message'=> 'There is no such product in the cart']);
    }
}