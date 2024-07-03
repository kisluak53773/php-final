<?php

namespace App\Controller;

use App\App;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Cart;
use DateTime;

header('Content-Type: application/json');

class OrderController
{
    public function create(): void
    {
        if(!$_POST['address'] || !$_POST['paymentMethod']) {
            http_response_code(400);
            echo json_encode(['message' => 'address or payment method is not provided']);

            return;
        }

        $status = $_POST['status'] ?? 'unpaid';
        $entityManager = App::db()->getEntityManager();

        $cart = $entityManager->getRepository(Cart::class)->find($_SESSION['cartId']);
        $user = $entityManager->getRepository(User::class)->find($_SESSION['userId']);

        if (!$cart || !$user) {
            http_response_code(401);
            echo json_encode(['message' => 'you are probably unathorized or your cart is empty']);

            return;
        }

        $productCollection = $cart->getProducts();

        if ($productCollection->isEmpty()) {
            http_response_code(400);
            echo json_encode(['message'=> 'cart can not be empty']);

            return;
        }

        $order = new Order();
        $order->setAddress($_POST['address']);
        $order->setPaymentMethod($_POST['paymentMethod']);
        $order->setStatus($status);
        $order->setCreatedAt(new DateTime('now'));
        $order->setUpdatedAt(new DateTime('now'));
        $order->setUser($user);

        foreach ($productCollection as $product) {
            $order->addProduct($product);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        http_response_code(201);
        echo json_encode(['message' => 'order created']);
    }
}