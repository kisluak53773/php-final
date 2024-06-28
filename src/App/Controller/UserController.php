<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\User;
use DateTime;

class UserController
{
    /**
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(): void
    {
        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setPassword(base64_encode($_POST['password']));
        $user->setCreatedAt(new DateTime('now'));
        $user->setUpdatedAt(new DateTime('now'));

        $entityManager = App::db()->getEntityManager();

        $entityManager->persist($user);

        $entityManager->flush();

        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    }
}