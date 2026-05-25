<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602100000_CreateDiaryTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create meals, diary_notes, and water_intake tables.';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('meals')) {
            $this->addSql('CREATE TABLE meals (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, food_name VARCHAR(100) NOT NULL, meal_type VARCHAR(50) NOT NULL, calories INT NOT NULL, protein INT NOT NULL, carbs INT NOT NULL, fat INT NOT NULL, date DATE NOT NULL, INDEX IDX_MEALS_USER (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE meals ADD CONSTRAINT FK_MEALS_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        }

        if (!$schema->hasTable('diary_notes')) {
            $this->addSql('CREATE TABLE diary_notes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DIARY_NOTES_USER (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE diary_notes ADD CONSTRAINT FK_DIARY_NOTES_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        }

        if (!$schema->hasTable('water_intake')) {
            $this->addSql('CREATE TABLE water_intake (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, glasses INT NOT NULL DEFAULT 0, INDEX IDX_WATER_INTAKE_USER (user_id), UNIQUE INDEX UNIQ_WATER_USER_DATE (user_id, date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE water_intake ADD CONSTRAINT FK_WATER_INTAKE_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('water_intake')) {
            $this->addSql('DROP TABLE water_intake');
        }
        if ($schema->hasTable('diary_notes')) {
            $this->addSql('DROP TABLE diary_notes');
        }
        if ($schema->hasTable('meals')) {
            $this->addSql('DROP TABLE meals');
        }
    }
}
