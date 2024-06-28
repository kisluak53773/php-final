<?php

namespace App\Controller;

use App\App;
use App\Entity\User;
use App\Entity\Order;
use DateTime;

class OrderController
{
    /**
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(): void
    {
        $entityManager = App::db()->getEntityManager();
        $userId = $_POST['userId'];
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            http_response_code(404);
            echo "User not found";

            exit;
        }

        $order  = new Order();

        $order->setProduct($_POST['product']);
        $order->setCreatedAt(new DateTime('now'));
        $order->setUpdatedAt(new DateTime('now'));

        $user->addOrder($order);
        $user->setUpdatedAt(new DateTime('now'));

        $entityManager->persist($user);

        $entityManager->flush();

        http_response_code(201);
        echo json_encode(['message' => 'Order created']);
    }
}