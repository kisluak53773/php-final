<?php

declare(strict_types=1);

namespace App;

class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db'     => [
                'dbname' => $env['POSTGRES_DB'],
                'user' => $env['POSTGRES_USER'],
                'password' => $env['POSTGRES_PASSWORD'],
                'host' => $env['POSTGRES_HOST'],
                'driver' => $env['DB_DRIVER'] ?? 'pdo_pgsql',
            ],
            'redis' =>[
                'scheme' => $env['REDIS_SCHEME'],
                'host' => $env['REDIS_HOST'],
                'port' =>(int) $env['REDIS_PORT'],
                'password' => $env['REDIS_PASSWORD'],
            ]
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}