<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260525005944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diary_notes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, note CLOB NOT NULL, created_at DATETIME NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_400406ADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_400406ADA76ED395 ON diary_notes (user_id)');
        $this->addSql('CREATE TABLE exercise_logs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, duration INTEGER NOT NULL, calories_burned INTEGER NOT NULL, date DATE NOT NULL, user_id INTEGER NOT NULL, exercise_id INTEGER NOT NULL, CONSTRAINT FK_A9AAA4EEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A9AAA4EEE934951A FOREIGN KEY (exercise_id) REFERENCES exercises (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A9AAA4EEA76ED395 ON exercise_logs (user_id)');
        $this->addSql('CREATE INDEX IDX_A9AAA4EEE934951A ON exercise_logs (exercise_id)');
        $this->addSql('CREATE TABLE exercises (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, category VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE meals (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, food_name VARCHAR(100) NOT NULL, meal_type VARCHAR(50) NOT NULL, calories INTEGER NOT NULL, protein INTEGER NOT NULL, carbs INTEGER NOT NULL, fat INTEGER NOT NULL, date DATE NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_E229E6EAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E229E6EAA76ED395 ON meals (user_id)');
        $this->addSql('CREATE TABLE water_intake (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, glasses INTEGER DEFAULT 0 NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_77832F8FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_77832F8FA76ED395 ON water_intake (user_id)');
        $this->addSql('CREATE UNIQUE INDEX user_date_unique ON water_intake (user_id, date)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__foods AS SELECT id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at FROM foods');
        $this->addSql('DROP TABLE foods');
        $this->addSql('CREATE TABLE foods (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(150) NOT NULL, category VARCHAR(50) NOT NULL, calories NUMERIC(6, 2) NOT NULL, protein NUMERIC(6, 2) NOT NULL, carbs NUMERIC(6, 2) NOT NULL, fat NUMERIC(6, 2) NOT NULL, serving VARCHAR(60) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, source VARCHAR(50) NOT NULL, source_product_id VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO foods (id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at) SELECT id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at FROM __temp__foods');
        $this->addSql('DROP TABLE __temp__foods');
        $this->addSql('CREATE INDEX idx_foods_category ON foods (category)');
        $this->addSql('CREATE INDEX idx_foods_name ON foods (name)');
        $this->addSql('CREATE UNIQUE INDEX ux_foods_source_product ON foods (source, source_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE diary_notes');
        $this->addSql('DROP TABLE exercise_logs');
        $this->addSql('DROP TABLE exercises');
        $this->addSql('DROP TABLE meals');
        $this->addSql('DROP TABLE water_intake');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TEMPORARY TABLE __temp__foods AS SELECT id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at FROM foods');
        $this->addSql('DROP TABLE foods');
        $this->addSql('CREATE TABLE foods (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(150) NOT NULL, category VARCHAR(50) NOT NULL, calories NUMERIC(6, 2) DEFAULT \'0\' NOT NULL, protein NUMERIC(6, 2) DEFAULT \'0\' NOT NULL, carbs NUMERIC(6, 2) DEFAULT \'0\' NOT NULL, fat NUMERIC(6, 2) DEFAULT \'0\' NOT NULL, serving VARCHAR(60) DEFAULT \'100g\' NOT NULL, image_url VARCHAR(255) DEFAULT NULL, source VARCHAR(50) DEFAULT \'openfoodfacts\' NOT NULL, source_product_id VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO foods (id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at) SELECT id, name, category, calories, protein, carbs, fat, serving, image_url, source, source_product_id, created_at, updated_at FROM __temp__foods');
        $this->addSql('DROP TABLE __temp__foods');
        $this->addSql('CREATE INDEX idx_foods_name ON foods (name)');
        $this->addSql('CREATE INDEX idx_foods_category ON foods (category)');
        $this->addSql('CREATE UNIQUE INDEX ux_foods_source_product ON foods (source, source_product_id)');
        $this->addSql('CREATE INDEX idx_foods_created_at ON foods (created_at)');
    }
}
