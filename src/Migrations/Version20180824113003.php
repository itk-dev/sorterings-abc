<?php

declare(strict_types=1);

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018–2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180824113003 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_by VARCHAR(255) DEFAULT NULL, ADD updated_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item_category ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_by VARCHAR(255) DEFAULT NULL, ADD updated_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item_description ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_by VARCHAR(255) DEFAULT NULL, ADD updated_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_by VARCHAR(255) DEFAULT NULL, ADD updated_by VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP created_at, DROP updated_at, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE item DROP created_at, DROP updated_at, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE item_category DROP created_at, DROP updated_at, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE item_description DROP created_at, DROP updated_at, DROP created_by, DROP updated_by');
    }
}
