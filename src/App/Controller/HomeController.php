<?php

declare(strict_types=1);

namespace App\Controller;

use App\App;
use App\Entity\Order;
use App\Entity\User;
use App\Service\EntityService;
use Throwable;
use App\Decorator\StandartSum;
use App\Decorator\ExtraSumm;
use App\Decorator\SuperExtraSum;

header("Content-Type: text/html; charset=UTF-8");

class HomeController
{
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION)) {
            echo App::twig()->render('home.html.twig', ['session' => $_SESSION, 'cookies' => $_COOKIE]);
        } else {
            echo App::twig()->render('home.html.twig', ['cookies' => $_COOKIE]);
        }
    }

    public function test(): void
    {
        echo "test";
    }

    public function findById(string $id): void
    {
        try {
            if (!$id) {
                echo App::twig()->render('error.html.twig');

                exit;
            }

            $cacheService = App::cacheService();

            if ($cacheService->has($_SERVER['REQUEST_URI'])) {
                echo $cacheService->get($_SERVER['REQUEST_URI']);

                exit;
            }

            $entityManager = App::db()->getEntityManager();
            $user = $entityManager->getRepository(User::class)->findAll();
            $order = $entityManager->getRepository(Order::class)->find($id);

            if (!$order) {
                echo App::twig()->render('error.html.twig');

                exit;
            }

            $renderResult = App::twig()->render('order.html.twig', ['order' => EntityService::convertOrderIntoArray($order)]);

            $cacheService->set($_SERVER['REQUEST_URI'], $renderResult);

            echo $renderResult;
        } catch (Throwable $e) {
            echo 'something went wrong';
        }
    }

    public function error(): void
    {
        echo App::twig()->render('error.html.twig');
    }

    public function deleteCookies(): void
    {
        if (isset($_POST)) {
            foreach ($_POST as $item) {
                unset($_COOKIE[$item]);
                setcookie($item, '', -1, '/');

                header('Location: /');
            }
        }
    }

    public function destroySession(): void
    {
        session_start();
        session_destroy();
        $_SESSION = [];

        header('Location: /');
    }

    public function cookie(): void
    {
        if (isset($_POST['key']) && isset($_POST['value']) && isset($_POST['time'])) {
            $time = is_int($_POST['time']) ? $_POST['time'] : 3600;
            setcookie($_POST['key'], $_POST['value'], time() + $time, '/');
            header("Location: /");
        } else {
            echo "Ты ввел не все данные";
        }
    }

    public function session(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_POST['key']) && isset($_POST['value'])) {
            $_SESSION[$_POST['key']] = $_POST['value'];
            header("Location: /");
        } else {
            echo "Ты ввел не все данные";
        }
    }

    public function home(): void
    {
        echo "Hello World!";
    }

    public function orderPage(): void
    {
        echo App::twig()->render('order.html.twig');
    }

    public function decorator():void
    {
        $decorator = new StandartSum();
        $decorator = new ExtraSumm($decorator);
        $decorator = new SuperExtraSum($decorator);

        echo $decorator->sort(40);
    }
}