<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910190011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alteration (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9FE087C48925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alteration_personage (alteration_id INT NOT NULL, personage_id INT NOT NULL, INDEX IDX_42A62F31242F17C7 (alteration_id), INDEX IDX_42A62F31EA8E3E4A (personage_id), PRIMARY KEY(alteration_id, personage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alteration_change (id INT AUTO_INCREMENT NOT NULL, alteration_id INT NOT NULL, attribute_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, value INT DEFAULT NULL, percentage INT DEFAULT NULL, INDEX IDX_4F74B076242F17C7 (alteration_id), INDEX IDX_4F74B076B6E62EFA (attribute_id), INDEX IDX_4F74B0765585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE armor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost (id INT AUTO_INCREMENT NOT NULL, money_id INT NOT NULL, item_information_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_182694FCBF29332C (money_id), INDEX IDX_182694FC3F1D96F5 (item_information_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(255) DEFAULT NULL, INDEX IDX_6956883F8925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damage_or_resistance (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, weapon_id INT DEFAULT NULL, armor_id INT DEFAULT NULL, spell_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_17DD25DCC54C8C93 (type_id), INDEX IDX_17DD25DC95B82273 (weapon_id), INDEX IDX_17DD25DCF5AA3663 (armor_id), INDEX IDX_17DD25DC479EC90D (spell_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damage_or_resistance_type (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8AC0C8938925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, point_id INT NOT NULL, spell_id INT NOT NULL, value INT NOT NULL, INDEX IDX_2D3A8DA6C028CEA2 (point_id), INDEX IDX_2D3A8DA6479EC90D (spell_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, item_information_id INT NOT NULL, personage_id INT NOT NULL, INDEX IDX_1F1B251E3F1D96F5 (item_information_id), INDEX IDX_1F1B251EEA8E3E4A (personage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_information (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE money (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_B7DF13E438248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, scope INT NOT NULL, INDEX IDX_D03FCD8D8925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell_attribute (spell_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_1ABB6ADC479EC90D (spell_id), INDEX IDX_1ABB6ADCB6E62EFA (attribute_id), PRIMARY KEY(spell_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell_personage (spell_id INT NOT NULL, personage_id INT NOT NULL, INDEX IDX_6CBEBE2479EC90D (spell_id), INDEX IDX_6CBEBE2EA8E3E4A (personage_id), PRIMARY KEY(spell_id, personage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon (id INT NOT NULL, scope INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon_attribute (weapon_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_18B3815D95B82273 (weapon_id), INDEX IDX_18B3815DB6E62EFA (attribute_id), PRIMARY KEY(weapon_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alteration ADD CONSTRAINT FK_9FE087C48925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE alteration_personage ADD CONSTRAINT FK_42A62F31242F17C7 FOREIGN KEY (alteration_id) REFERENCES alteration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alteration_personage ADD CONSTRAINT FK_42A62F31EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alteration_change ADD CONSTRAINT FK_4F74B076242F17C7 FOREIGN KEY (alteration_id) REFERENCES alteration (id)');
        $this->addSql('ALTER TABLE alteration_change ADD CONSTRAINT FK_4F74B076B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE alteration_change ADD CONSTRAINT FK_4F74B0765585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE armor ADD CONSTRAINT FK_BF27FEFCBF396750 FOREIGN KEY (id) REFERENCES item_information (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FCBF29332C FOREIGN KEY (money_id) REFERENCES money (id)');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FC3F1D96F5 FOREIGN KEY (item_information_id) REFERENCES item_information (id)');
        $this->addSql('ALTER TABLE currency ADD CONSTRAINT FK_6956883F8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DCC54C8C93 FOREIGN KEY (type_id) REFERENCES damage_or_resistance_type (id)');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DC95B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id)');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DCF5AA3663 FOREIGN KEY (armor_id) REFERENCES armor (id)');
        $this->addSql('ALTER TABLE damage_or_resistance ADD CONSTRAINT FK_17DD25DC479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE damage_or_resistance_type ADD CONSTRAINT FK_8AC0C8938925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6C028CEA2 FOREIGN KEY (point_id) REFERENCES point (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E3F1D96F5 FOREIGN KEY (item_information_id) REFERENCES item_information (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EEA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('ALTER TABLE money ADD CONSTRAINT FK_B7DF13E438248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE spell ADD CONSTRAINT FK_D03FCD8D8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE spell_attribute ADD CONSTRAINT FK_1ABB6ADC479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spell_attribute ADD CONSTRAINT FK_1ABB6ADCB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spell_personage ADD CONSTRAINT FK_6CBEBE2479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spell_personage ADD CONSTRAINT FK_6CBEBE2EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weapon ADD CONSTRAINT FK_6933A7E6BF396750 FOREIGN KEY (id) REFERENCES item_information (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weapon_attribute ADD CONSTRAINT FK_18B3815D95B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE weapon_attribute ADD CONSTRAINT FK_18B3815DB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dice ADD launcher_id INT NOT NULL, ADD world_id INT NOT NULL, CHANGE personage_id personage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE2542724B909 FOREIGN KEY (launcher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE2548925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_A10EE2542724B909 ON dice (launcher_id)');
        $this->addSql('CREATE INDEX IDX_A10EE2548925311C ON dice (world_id)');
        $this->addSql('ALTER TABLE personage ADD biography LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alteration DROP FOREIGN KEY FK_9FE087C48925311C');
        $this->addSql('ALTER TABLE alteration_personage DROP FOREIGN KEY FK_42A62F31242F17C7');
        $this->addSql('ALTER TABLE alteration_personage DROP FOREIGN KEY FK_42A62F31EA8E3E4A');
        $this->addSql('ALTER TABLE alteration_change DROP FOREIGN KEY FK_4F74B076242F17C7');
        $this->addSql('ALTER TABLE alteration_change DROP FOREIGN KEY FK_4F74B076B6E62EFA');
        $this->addSql('ALTER TABLE alteration_change DROP FOREIGN KEY FK_4F74B0765585C142');
        $this->addSql('ALTER TABLE armor DROP FOREIGN KEY FK_BF27FEFCBF396750');
        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FCBF29332C');
        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FC3F1D96F5');
        $this->addSql('ALTER TABLE currency DROP FOREIGN KEY FK_6956883F8925311C');
        $this->addSql('ALTER TABLE damage_or_resistance DROP FOREIGN KEY FK_17DD25DCC54C8C93');
        $this->addSql('ALTER TABLE damage_or_resistance DROP FOREIGN KEY FK_17DD25DC95B82273');
        $this->addSql('ALTER TABLE damage_or_resistance DROP FOREIGN KEY FK_17DD25DCF5AA3663');
        $this->addSql('ALTER TABLE damage_or_resistance DROP FOREIGN KEY FK_17DD25DC479EC90D');
        $this->addSql('ALTER TABLE damage_or_resistance_type DROP FOREIGN KEY FK_8AC0C8938925311C');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6C028CEA2');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6479EC90D');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E3F1D96F5');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EEA8E3E4A');
        $this->addSql('ALTER TABLE money DROP FOREIGN KEY FK_B7DF13E438248176');
        $this->addSql('ALTER TABLE spell DROP FOREIGN KEY FK_D03FCD8D8925311C');
        $this->addSql('ALTER TABLE spell_attribute DROP FOREIGN KEY FK_1ABB6ADC479EC90D');
        $this->addSql('ALTER TABLE spell_attribute DROP FOREIGN KEY FK_1ABB6ADCB6E62EFA');
        $this->addSql('ALTER TABLE spell_personage DROP FOREIGN KEY FK_6CBEBE2479EC90D');
        $this->addSql('ALTER TABLE spell_personage DROP FOREIGN KEY FK_6CBEBE2EA8E3E4A');
        $this->addSql('ALTER TABLE weapon DROP FOREIGN KEY FK_6933A7E6BF396750');
        $this->addSql('ALTER TABLE weapon_attribute DROP FOREIGN KEY FK_18B3815D95B82273');
        $this->addSql('ALTER TABLE weapon_attribute DROP FOREIGN KEY FK_18B3815DB6E62EFA');
        $this->addSql('DROP TABLE alteration');
        $this->addSql('DROP TABLE alteration_personage');
        $this->addSql('DROP TABLE alteration_change');
        $this->addSql('DROP TABLE armor');
        $this->addSql('DROP TABLE cost');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE damage_or_resistance');
        $this->addSql('DROP TABLE damage_or_resistance_type');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_information');
        $this->addSql('DROP TABLE money');
        $this->addSql('DROP TABLE spell');
        $this->addSql('DROP TABLE spell_attribute');
        $this->addSql('DROP TABLE spell_personage');
        $this->addSql('DROP TABLE weapon');
        $this->addSql('DROP TABLE weapon_attribute');
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE2542724B909');
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE2548925311C');
        $this->addSql('DROP INDEX IDX_A10EE2542724B909 ON dice');
        $this->addSql('DROP INDEX IDX_A10EE2548925311C ON dice');
        $this->addSql('ALTER TABLE dice DROP launcher_id, DROP world_id, CHANGE personage_id personage_id INT NOT NULL');
        $this->addSql('ALTER TABLE personage DROP biography');
    }
}
