<?php

declare(strict_types=1);

namespace App;

use App\Router\RouterMapper;
use Dotenv\Dotenv;
use App\Service\ExceptionLogger;
use Monolog\Logger;
use App\Router\Exception\RouterException;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Predis\Client;
use App\Service\RedisCacheService;
use App\Service\Contracts\ContainerTestInterface;
use App\Service\ContainerTest;
use App\Router\Wrapper\Request;

class App
{
    private static DB $db;
    private static RedisCacheService $redisCacheService;
    private static Container $container;
    private static Request $request;
    private Config $config;
    private ExceptionLogger $exceptionLogger;
    private static Environment $twig;

    public function __construct(Request $request)
    {
        self::$request = $request;
    }

    public static function db(): DB
    {
        return self::$db;
    }

    public static function twig(): Environment
    {
        return self::$twig;
    }

    public static function cacheService(): RedisCacheService
    {
        return self::$redisCacheService;
    }

    public static function container(): Container
    {
        return self::$container;
    }

    public static function request(): Request
    {
        return self::$request;
    }

    public function boot(): self
    {
        $dotenv = Dotenv::createImmutable(dirname('/../' .__DIR__));
        $dotenv->load();

        $loader = new FilesystemLoader(__DIR__ . '/../views');
        self::$twig = new Environment($loader, [
            'cache' => false,
        ]);

        $logger = new Logger('router');
        $this->exceptionLogger = new ExceptionLogger($logger);

        $this->config = new Config($_ENV);
        self::$db = new DB($this->config->db ?? []);

        $client = new Client($this->config->redis ?? []);
        self::$redisCacheService = new RedisCacheService($client);
        
        $container = new Container();
        $container->set(ContainerTestInterface::class, ContainerTest::class);
        self::$container = $container;

        session_start();

        $_SESSION['userId'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['cartId'] =2;

        return $this;
    }

    public function run(): void
    {
        try {
            RouterMapper::handleRoute(self::$request);
        } catch (RouterException $e) {
            $this->exceptionLogger->log($e);
        }
    }
}