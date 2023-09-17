<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828233944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE2549502F0B');
        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FA7AEFFB8925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE number_of_attribute (id INT AUTO_INCREMENT NOT NULL, attribute_id INT NOT NULL, personage_id INT NOT NULL, value INT NOT NULL, INDEX IDX_35580BC4B6E62EFA (attribute_id), INDEX IDX_35580BC4EA8E3E4A (personage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE number_of_point (id INT AUTO_INCREMENT NOT NULL, point_id INT NOT NULL, personage_id INT NOT NULL, current INT NOT NULL, min INT NOT NULL, max INT NOT NULL, INDEX IDX_88C25F45C028CEA2 (point_id), INDEX IDX_88C25F45EA8E3E4A (personage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE number_of_skill (id INT AUTO_INCREMENT NOT NULL, skill_id INT NOT NULL, personage_id INT NOT NULL, value INT NOT NULL, INDEX IDX_615A48165585C142 (skill_id), INDEX IDX_615A4816EA8E3E4A (personage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, acronym VARCHAR(3) NOT NULL, INDEX IDX_B7A5F3248925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5E3DE477B6E62EFA (attribute_id), INDEX IDX_5E3DE4778925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute ADD CONSTRAINT FK_FA7AEFFB8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE number_of_attribute ADD CONSTRAINT FK_35580BC4B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE number_of_attribute ADD CONSTRAINT FK_35580BC4EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('ALTER TABLE number_of_point ADD CONSTRAINT FK_88C25F45C028CEA2 FOREIGN KEY (point_id) REFERENCES point (id)');
        $this->addSql('ALTER TABLE number_of_point ADD CONSTRAINT FK_88C25F45EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('ALTER TABLE number_of_skill ADD CONSTRAINT FK_615A48165585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE number_of_skill ADD CONSTRAINT FK_615A4816EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F3248925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE477B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE4778925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F21243879502F0B');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F2124387EA8E3E4A');
        $this->addSql('ALTER TABLE stat DROP FOREIGN KEY FK_20B8FF217B185B4D');
        $this->addSql('DROP TABLE number_of_stat');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP INDEX IDX_A10EE2549502F0B ON dice');
        $this->addSql('ALTER TABLE dice CHANGE stat_id attribute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE254B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('CREATE INDEX IDX_A10EE254B6E62EFA ON dice (attribute_id)');
        $this->addSql('ALTER TABLE personage ADD race VARCHAR(255) DEFAULT NULL, ADD alignment VARCHAR(255) DEFAULT NULL, ADD class VARCHAR(255) DEFAULT NULL, ADD inventory LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE token ADD layer INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE254B6E62EFA');
        $this->addSql('CREATE TABLE number_of_stat (id INT AUTO_INCREMENT NOT NULL, personage_id INT NOT NULL, stat_id INT NOT NULL, value INT NOT NULL, INDEX IDX_F21243879502F0B (stat_id), INDEX IDX_F2124387EA8E3E4A (personage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, parent_stat_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, depth INT NOT NULL, INDEX IDX_20B8FF217B185B4D (parent_stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F21243879502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F2124387EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('ALTER TABLE stat ADD CONSTRAINT FK_20B8FF217B185B4D FOREIGN KEY (parent_stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE attribute DROP FOREIGN KEY FK_FA7AEFFB8925311C');
        $this->addSql('ALTER TABLE number_of_attribute DROP FOREIGN KEY FK_35580BC4B6E62EFA');
        $this->addSql('ALTER TABLE number_of_attribute DROP FOREIGN KEY FK_35580BC4EA8E3E4A');
        $this->addSql('ALTER TABLE number_of_point DROP FOREIGN KEY FK_88C25F45C028CEA2');
        $this->addSql('ALTER TABLE number_of_point DROP FOREIGN KEY FK_88C25F45EA8E3E4A');
        $this->addSql('ALTER TABLE number_of_skill DROP FOREIGN KEY FK_615A48165585C142');
        $this->addSql('ALTER TABLE number_of_skill DROP FOREIGN KEY FK_615A4816EA8E3E4A');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F3248925311C');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE477B6E62EFA');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE4778925311C');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE number_of_attribute');
        $this->addSql('DROP TABLE number_of_point');
        $this->addSql('DROP TABLE number_of_skill');
        $this->addSql('DROP TABLE point');
        $this->addSql('DROP TABLE skill');
        $this->addSql('ALTER TABLE token DROP layer');
        $this->addSql('DROP INDEX IDX_A10EE254B6E62EFA ON dice');
        $this->addSql('ALTER TABLE dice CHANGE attribute_id stat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE2549502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('CREATE INDEX IDX_A10EE2549502F0B ON dice (stat_id)');
        $this->addSql('ALTER TABLE personage DROP race, DROP alignment, DROP class, DROP inventory');
    }
}
