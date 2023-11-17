<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117154828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_prefab ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE item_prefab ADD CONSTRAINT FK_20F380338925311C FOREIGN KEY (world_id) REFERENCES world (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_20F380338925311C ON item_prefab (world_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item_prefab DROP CONSTRAINT FK_20F380338925311C');
        $this->addSql('DROP INDEX IDX_20F380338925311C');
        $this->addSql('ALTER TABLE item_prefab DROP world_id');
    }
}
