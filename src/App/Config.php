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
                'host'     => $env['POSTGRES_HOST'],
                'user'     => $env['POSTGRES_USER'],
                'password' => $env['POSTGRES_PASSWORD'],
                'dbname'   => $env['POSTGRES_DB'],
                'driver'   => $env['DB_DRIVER'] ?? 'pdo_pgsql',
            ],
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}