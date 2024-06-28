<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\JsonFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = new JsonFile('migrations.json');
$params = [
    'host'     => 'localhost',
    'user'     => $_ENV['POSTGRES_USER'],
    'password' => $_ENV['POSTGRES_PASSWORD'],
    'dbname'   => $_ENV['POSTGRES_DB'],
    'driver'   => $_ENV['DB_DRIVER'] ?? 'pdo_pgsql',
];
$paths = [__DIR__ . '/App/Entity'];
$isDevMode = true;

$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($params);

$entityManager = new EntityManager($connection, $ORMConfig);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
