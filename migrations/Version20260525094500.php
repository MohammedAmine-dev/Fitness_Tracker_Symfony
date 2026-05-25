<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260525094500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table for authentication.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('user')) {
            return;
        }

        $user = $schema->createTable('user');
        $user->addColumn('id', 'integer', ['autoincrement' => true]);
        $user->setPrimaryKey(['id']);
        $user->addColumn('email', 'string', ['length' => 180]);
        $user->addUniqueIndex(['email'], 'UNIQ_8D93D649E7927C74');
        $user->addColumn('roles', 'json');
        $user->addColumn('password', 'string', ['length' => 255]);
        $user->addColumn('name', 'string', ['length' => 255]);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('user')) {
            $schema->dropTable('user');
        }
    }
}
