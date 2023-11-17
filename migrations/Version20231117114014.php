<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117114014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE damage_or_resistance DROP CONSTRAINT fk_17dd25dcf5aa3663');
        $this->addSql('ALTER TABLE damage_or_resistance DROP CONSTRAINT fk_17dd25dc95b82273');
        $this->addSql('CREATE TABLE armor_prefab (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE weapon_prefab (id INT NOT NULL, scope INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE weapon_prefab_attribute (weapon_prefab_id INT NOT NULL, attribute_id INT NOT NULL, PRIMARY KEY(weapon_prefab_id, attribute_id))');
        $this->addSql('CREATE INDEX IDX_5ABAD5DFB148DD62 ON weapon_prefab_attribute (weapon_prefab_id)');
        $this->addSql('CREATE INDEX IDX_5ABAD5DFB6E62EFA ON weapon_prefab_attribute (attribute_id)');
        $this->addSql('ALTER TABLE armor_prefab ADD CONSTRAINT FK_E58F9763BF396750 FOREIGN KEY (id) REFERENCES item_prefab (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon_prefab ADD CONSTRAINT FK_9BFFDADBBF396750 FOREIGN KEY (id) REFERENCES item_prefab (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon_prefab_attribute ADD CONSTRAINT FK_5ABAD5DFB148DD62 FOREIGN KEY (weapon_prefab_id) REFERENCES weapon_prefab (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon_prefab_attribute ADD CONSTRAINT FK_5ABAD5DFB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE armor DROP CONSTRAINT fk_bf27fefcbf396750');
        $this->addSql('ALTER TABLE weapon DROP CONSTRAINT fk_6933a7e6bf396750');
        $this->addSql('ALTER TABLE weapon_attribute DROP CONSTRAINT fk_18b3815d95b82273');
        $this->addSql('ALTER TABLE weapon_attribute DROP CONSTRAINT fk_18b3815db6e62efa');
        $this->addSql('DROP TABLE armor');
        $this->addSql('DROP TABLE weapon');
        $this->addSql('DROP TABLE weapon_attribute');
        $this->addSql('DROP INDEX idx_17dd25dcf5aa3663');
        $this->addSql('DROP INDEX idx_17dd25dc95b82273');
        $this->addSql('ALTER TABLE damage_or_resistance ADD weapon_prefab_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE damage_or_resistance ADD armor_prefab_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE damage_or_resistance DROP weapon_id');
        $this->addSql('ALTER TABLE damage_or_resistance DROP armor_id');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DCB148DD62 FOREIGN KEY (weapon_prefab_id) REFERENCES weapon_prefab (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DC6FD732C0 FOREIGN KEY (armor_prefab_id) REFERENCES armor_prefab (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_17DD25DCB148DD62 ON damage_or_resistance (weapon_prefab_id)');
        $this->addSql('CREATE INDEX IDX_17DD25DC6FD732C0 ON damage_or_resistance (armor_prefab_id)');
        $this->addSql('ALTER TABLE item_prefab RENAME COLUMN item_type TO item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE damage_or_resistance DROP CONSTRAINT FK_17DD25DC6FD732C0');
        $this->addSql('ALTER TABLE damage_or_resistance DROP CONSTRAINT FK_17DD25DCB148DD62');
        $this->addSql('CREATE TABLE armor (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE weapon (id INT NOT NULL, scope INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE weapon_attribute (weapon_id INT NOT NULL, attribute_id INT NOT NULL, PRIMARY KEY(weapon_id, attribute_id))');
        $this->addSql('CREATE INDEX idx_18b3815db6e62efa ON weapon_attribute (attribute_id)');
        $this->addSql('CREATE INDEX idx_18b3815d95b82273 ON weapon_attribute (weapon_id)');
        $this->addSql('ALTER TABLE armor ADD CONSTRAINT fk_bf27fefcbf396750 FOREIGN KEY (id) REFERENCES item_prefab (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon ADD CONSTRAINT fk_6933a7e6bf396750 FOREIGN KEY (id) REFERENCES item_prefab (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon_attribute ADD CONSTRAINT fk_18b3815d95b82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weapon_attribute ADD CONSTRAINT fk_18b3815db6e62efa FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE armor_prefab DROP CONSTRAINT FK_E58F9763BF396750');
        $this->addSql('ALTER TABLE weapon_prefab DROP CONSTRAINT FK_9BFFDADBBF396750');
        $this->addSql('ALTER TABLE weapon_prefab_attribute DROP CONSTRAINT FK_5ABAD5DFB148DD62');
        $this->addSql('ALTER TABLE weapon_prefab_attribute DROP CONSTRAINT FK_5ABAD5DFB6E62EFA');
        $this->addSql('DROP TABLE armor_prefab');
        $this->addSql('DROP TABLE weapon_prefab');
        $this->addSql('DROP TABLE weapon_prefab_attribute');
        $this->addSql('ALTER TABLE item_prefab RENAME COLUMN item TO item_type');
        $this->addSql('DROP INDEX IDX_17DD25DCB148DD62');
        $this->addSql('DROP INDEX IDX_17DD25DC6FD732C0');
        $this->addSql('ALTER TABLE damage_or_resistance ADD weapon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE damage_or_resistance ADD armor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE damage_or_resistance DROP weapon_prefab_id');
        $this->addSql('ALTER TABLE damage_or_resistance DROP armor_prefab_id');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT fk_17dd25dc95b82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT fk_17dd25dcf5aa3663 FOREIGN KEY (armor_id) REFERENCES armor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_17dd25dcf5aa3663 ON damage_or_resistance (armor_id)');
        $this->addSql('CREATE INDEX idx_17dd25dc95b82273 ON damage_or_resistance (weapon_id)');
    }
}
