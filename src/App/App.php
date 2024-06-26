<?php

declare(strict_types=1);

namespace App;

use App\Router\RouterMapper;
use Dotenv\Dotenv;
use App\Service\ExceptionLogger;
use Monolog\Logger;
use App\Router\Exception\RouterException;

class App
{
    private static DB $db;
    private Config $config;
    private ExceptionLogger $exceptionLogger;

    public function __construct(
        private readonly array $request = [],
    )
    {}

    public static function db(): DB
    {
        return self::$db;
    }

    public function boot(): self
    {
        $dotenv = Dotenv::createImmutable(dirname('/../' .__DIR__));
        $dotenv->load();
        $this->config = new Config($_ENV);
        $logger = new Logger('router');
        $this->exceptionLogger = new ExceptionLogger($logger);
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