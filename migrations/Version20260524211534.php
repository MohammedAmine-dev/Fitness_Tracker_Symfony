<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260524211534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('goal')) {
            $goal = $schema->createTable('goal');
            $goal->addColumn('id', 'integer', ['autoincrement' => true]);
            $goal->setPrimaryKey(['id']);
            $goal->addColumn('target_weight', 'float', ['notnull' => false]);
            $goal->addColumn('daily_calories', 'integer', ['notnull' => false]);
            $goal->addColumn('weekly_workouts', 'integer', ['notnull' => false]);
            $goal->addColumn('user_id', 'integer', ['notnull' => true]);
            $goal->addUniqueIndex(['user_id'], 'UNIQ_FCDCEB2EA76ED395');
            $goal->addForeignKeyConstraint('user', ['user_id'], ['id'], [], 'FK_FCDCEB2EA76ED395');
        }

        if (!$schema->hasTable('weight_log')) {
            $weightLog = $schema->createTable('weight_log');
            $weightLog->addColumn('id', 'integer', ['autoincrement' => true]);
            $weightLog->setPrimaryKey(['id']);
            $weightLog->addColumn('weight', 'float', ['notnull' => true]);
            $weightLog->addColumn('date', 'date', ['notnull' => true]);
            $weightLog->addColumn('user_id', 'integer', ['notnull' => true]);
            $weightLog->addIndex(['user_id'], 'IDX_6BBB9E9CA76ED395');
            $weightLog->addForeignKeyConstraint('user', ['user_id'], ['id'], [], 'FK_6BBB9E9CA76ED395');
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('weight_log')) {
            $schema->dropTable('weight_log');
        }

        if ($schema->hasTable('goal')) {
            $schema->dropTable('goal');
        }
    }
}
