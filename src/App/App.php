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

class App
{
    private static DB $db;
    private Config $config;
    private ExceptionLogger $exceptionLogger;
    private static Environment $twig;

    public function __construct(
        private readonly array $request = [],
    )
    {}

    public static function db(): DB
    {
        return self::$db;
    }

    public static function twig(): Environment
    {
        return self::$twig;
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

        return $this;
    }

    public function run(): void
    {
        try {
            RouterMapper::handleRoute($this->request['uri'], $this->request['method']);
        } catch (RouterException $e) {
            $this->exceptionLogger->log($e);
        }
    }
}