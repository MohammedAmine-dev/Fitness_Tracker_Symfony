<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602101000_AddUserNameColumn extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add name column to user table if missing.';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('user')) {
            return;
        }

        $table = $schema->getTable('user');
        if (!$table->hasColumn('name')) {
            $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL DEFAULT ""');
        }

        if ($table->hasColumn('full_name')) {
            $this->addSql('UPDATE user SET name = full_name WHERE name = "" OR name IS NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if (!$schema->hasTable('user')) {
            return;
        }

        $table = $schema->getTable('user');
        if ($table->hasColumn('name')) {
            $this->addSql('ALTER TABLE user DROP name');
        }
    }
}
