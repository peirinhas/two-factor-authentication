<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622191551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates `authenticate` table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE authenticate (
                id CHAR(36) NOT NULL PRIMARY KEY,
                mobile CHAR(9) NOT NULL,
                token CHAR(4) NOT NULL,
                active bool NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                limit_at DATETIME NOT NULL,
                used_at DATETIME NULL,
                INDEX IDX_authenticate_mobile (mobile)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB'
        );

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE authenticate');
    }
}
