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
            echo App::twig()->render('error.html.twig', ['error' => '400', 'message' => 'required data is not provided']);

            return;
        }

        $status = $_POST['status'] ?? 'unpaid';
        $entityManager = App::db()->getEntityManager();

        $cart = $entityManager->getRepository(Cart::class)->find($_SESSION['cartId']);
        $user = $entityManager->getRepository(User::class)->find($_SESSION['userId']);

        if (!$cart || !$user) {
            echo App::twig()->render('error.html.twig', ['error' => '401', 'message' => 'you are probably unathorized or your cart is empty']);

            return;
        }

        $productCollection = $cart->getProducts();

        if ($productCollection->isEmpty()) {
            echo App::twig()->render('error.html.twig', ['error' => '400', 'message' => 'cart is empty']);

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
        $productCollection->clear();
        $entityManager->flush();

        header('Location: /');
    }
}