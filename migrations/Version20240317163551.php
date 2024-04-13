<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317163551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asset ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asset DROP image');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2AF5A5C3DA5256D ON asset (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE asset DROP CONSTRAINT FK_2AF5A5C3DA5256D');
        $this->addSql('DROP INDEX UNIQ_2AF5A5C3DA5256D');
        $this->addSql('ALTER TABLE asset ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE asset DROP image_id');
    }
}
