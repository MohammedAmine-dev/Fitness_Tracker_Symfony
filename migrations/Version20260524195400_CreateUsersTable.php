<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Creates the `users` table required by the Symfony Security authentication system.
 *
 * Columns
 * -------
 *  id         BIGINT PK AUTO_INCREMENT
 *  name       VARCHAR(100)  NOT NULL
 *  email      VARCHAR(150)  NOT NULL  UNIQUE
 *  password   VARCHAR(255)  NOT NULL  (bcrypt hash, never plain text)
 *  roles      JSON          NOT NULL  (e.g. ["ROLE_ADMIN"])
 *  created_at DATETIME      NOT NULL  (DATETIME_IMMUTABLE mapped type)
 */
final class Version20260524195400_CreateUsersTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table for Symfony Security authentication.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE users (
                id         BIGINT       NOT NULL AUTO_INCREMENT,
                name       VARCHAR(100) NOT NULL,
                email      VARCHAR(150) NOT NULL,
                password   VARCHAR(255) NOT NULL,
                roles      JSON         NOT NULL,
                created_at DATETIME     NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                PRIMARY KEY (id),
                UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email)
            ) DEFAULT CHARACTER SET utf8mb4
              COLLATE `utf8mb4_unicode_ci`
              ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
