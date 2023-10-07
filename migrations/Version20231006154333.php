<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231006154333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lighting_wall_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lighting_wall (id INT NOT NULL, map_id INT NOT NULL, start_x INT NOT NULL, end_x INT NOT NULL, start_y INT NOT NULL, end_y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B178FD6253C55F64 ON lighting_wall (map_id)');
        $this->addSql('ALTER TABLE lighting_wall ADD CONSTRAINT FK_B178FD6253C55F64 FOREIGN KEY (map_id) REFERENCES map (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE world DROP server_identifier');
        $this->addSql('ALTER TABLE world DROP dice_channel_identifier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lighting_wall_id_seq CASCADE');
        $this->addSql('ALTER TABLE lighting_wall DROP CONSTRAINT FK_B178FD6253C55F64');
        $this->addSql('DROP TABLE lighting_wall');
        $this->addSql('ALTER TABLE world ADD server_identifier VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE world ADD dice_channel_identifier VARCHAR(255) DEFAULT NULL');
    }
}
