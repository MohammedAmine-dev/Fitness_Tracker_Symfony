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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, target_weight DOUBLE PRECISION DEFAULT NULL, daily_calories INT DEFAULT NULL, weekly_workouts INT DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_FCDCEB2EA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE weight_log (id INT AUTO_INCREMENT NOT NULL, weight DOUBLE PRECISION NOT NULL, date DATE NOT NULL, user_id INT NOT NULL, INDEX IDX_6BBB9E9CA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE weight_log ADD CONSTRAINT FK_6BBB9E9CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2EA76ED395');
        $this->addSql('ALTER TABLE weight_log DROP FOREIGN KEY FK_6BBB9E9CA76ED395');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE weight_log');
    }
}
