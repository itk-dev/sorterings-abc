<?php

declare(strict_types=1);

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180822090851 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, description_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1F1B251ED9F966B (description_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_item_category (item_id INT NOT NULL, item_category_id INT NOT NULL, INDEX IDX_86ED79D1126F525E (item_id), INDEX IDX_86ED79D1F22EC5D4 (item_category_id), PRIMARY KEY(item_id, item_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_description (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED9F966B FOREIGN KEY (description_id) REFERENCES item_description (id)');
        $this->addSql('ALTER TABLE item_item_category ADD CONSTRAINT FK_86ED79D1126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_item_category ADD CONSTRAINT FK_86ED79D1F22EC5D4 FOREIGN KEY (item_category_id) REFERENCES item_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_item_category DROP FOREIGN KEY FK_86ED79D1126F525E');
        $this->addSql('ALTER TABLE item_item_category DROP FOREIGN KEY FK_86ED79D1F22EC5D4');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED9F966B');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_item_category');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('DROP TABLE item_description');
    }
}
