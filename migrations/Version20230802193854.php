<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802193854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F2124387217BBB47');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE254217BBB47');
        $this->addSql('CREATE TABLE connection (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, user_id INT NOT NULL, current_map_id INT NOT NULL, is_game_master TINYINT(1) NOT NULL, is_connected TINYINT(1) NOT NULL, INDEX IDX_29F773668925311C (world_id), INDEX IDX_29F77366A76ED395 (user_id), INDEX IDX_29F77366540C850A (current_map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personage (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, world_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E60A6EC5A76ED395 (user_id), INDEX IDX_E60A6EC58925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE world (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F773668925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F77366A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F77366540C850A FOREIGN KEY (current_map_id) REFERENCES map (id)');
        $this->addSql('ALTER TABLE personage ADD CONSTRAINT FK_E60A6EC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personage ADD CONSTRAINT FK_E60A6EC58925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP INDEX IDX_A10EE254217BBB47 ON dice');
        $this->addSql('ALTER TABLE dice CHANGE person_id personage_id INT NOT NULL');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE254EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('CREATE INDEX IDX_A10EE254EA8E3E4A ON dice (personage_id)');
        $this->addSql('ALTER TABLE map ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE map ADD CONSTRAINT FK_93ADAABB8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_93ADAABB8925311C ON map (world_id)');
        $this->addSql('DROP INDEX IDX_F2124387217BBB47 ON number_of_stat');
        $this->addSql('ALTER TABLE number_of_stat CHANGE person_id personage_id INT NOT NULL');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F2124387EA8E3E4A FOREIGN KEY (personage_id) REFERENCES personage (id)');
        $this->addSql('CREATE INDEX IDX_F2124387EA8E3E4A ON number_of_stat (personage_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64953C55F64');
        $this->addSql('DROP INDEX UNIQ_8D93D649217BBB47 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64953C55F64 ON user');
        $this->addSql('ALTER TABLE user DROP person_id, DROP map_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE254EA8E3E4A');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F2124387EA8E3E4A');
        $this->addSql('ALTER TABLE map DROP FOREIGN KEY FK_93ADAABB8925311C');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F773668925311C');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F77366A76ED395');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F77366540C850A');
        $this->addSql('ALTER TABLE personage DROP FOREIGN KEY FK_E60A6EC5A76ED395');
        $this->addSql('ALTER TABLE personage DROP FOREIGN KEY FK_E60A6EC58925311C');
        $this->addSql('DROP TABLE connection');
        $this->addSql('DROP TABLE personage');
        $this->addSql('DROP TABLE world');
        $this->addSql('DROP INDEX IDX_F2124387EA8E3E4A ON number_of_stat');
        $this->addSql('ALTER TABLE number_of_stat CHANGE personage_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F2124387217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_F2124387217BBB47 ON number_of_stat (person_id)');
        $this->addSql('ALTER TABLE user ADD person_id INT DEFAULT NULL, ADD map_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64953C55F64 FOREIGN KEY (map_id) REFERENCES map (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649217BBB47 ON user (person_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64953C55F64 ON user (map_id)');
        $this->addSql('DROP INDEX IDX_93ADAABB8925311C ON map');
        $this->addSql('ALTER TABLE map DROP world_id');
        $this->addSql('DROP INDEX IDX_A10EE254EA8E3E4A ON dice');
        $this->addSql('ALTER TABLE dice CHANGE personage_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE254217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_A10EE254217BBB47 ON dice (person_id)');
    }
}
