<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509173303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE camera camera VARCHAR(255) DEFAULT NULL, CHANGE battery battery INT DEFAULT NULL, CHANGE screen screen VARCHAR(255) DEFAULT NULL, CHANGE ram ram INT DEFAULT NULL, CHANGE memory memory INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone DROP created_at, DROP updated_at, CHANGE camera camera VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE battery battery INT DEFAULT NULL, CHANGE screen screen VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE ram ram INT DEFAULT NULL, CHANGE memory memory INT DEFAULT NULL');
    }
}
