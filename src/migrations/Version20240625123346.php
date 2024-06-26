<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625123346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $users = $schema->createTable('users');

        $users->addColumn('user_id', Types::INTEGER)->setAutoincrement(true);
        $users->setPrimaryKey(['user_id']);
        $users->addColumn('email', Types::STRING)->setNotnull(true);
        $users->addColumn('password', Types::STRING)->setNotnull(true);
        $users->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $users->addColumn('updated_at', Types::DATETIME_MUTABLE);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
