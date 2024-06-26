<?php

declare(strict_types = 1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DB
{
    private Connection $connection;
    private EntityManager $entityManager;

    public function __construct(array $config)
    {
        $this->connection = DriverManager::getConnection($config);
        $paths = [__DIR__ . '/Entity'];
        $ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths);
        $this->entityManager = new EntityManager($this->connection, $ORMConfig);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}