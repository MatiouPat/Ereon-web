<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724042424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asset (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token ADD asset_id INT DEFAULT NULL, DROP view');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B5DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id)');
        $this->addSql('CREATE INDEX IDX_5F37A13B5DA1941 ON token (asset_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B5DA1941');
        $this->addSql('DROP TABLE asset');
        $this->addSql('DROP INDEX IDX_5F37A13B5DA1941 ON token');
        $this->addSql('ALTER TABLE token ADD view VARCHAR(255) NOT NULL, DROP asset_id');
    }
}
