<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720154602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dice (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, stat_id INT DEFAULT NULL, dice_number INT NOT NULL, brut_value INT NOT NULL, final_value INT NOT NULL, computation VARCHAR(255) NOT NULL, INDEX IDX_A10EE254217BBB47 (person_id), INDEX IDX_A10EE2549502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE number_of_stat (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, stat_id INT NOT NULL, value INT NOT NULL, INDEX IDX_F2124387217BBB47 (person_id), INDEX IDX_F21243879502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_music (playlist_id INT NOT NULL, music_id INT NOT NULL, INDEX IDX_6E4E3B096BBD148 (playlist_id), INDEX IDX_6E4E3B09399BBB13 (music_id), PRIMARY KEY(playlist_id, music_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, parent_stat_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, depth INT NOT NULL, INDEX IDX_20B8FF217B185B4D (parent_stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, view VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, top_position INT NOT NULL, left_position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_map (token_id INT NOT NULL, map_id INT NOT NULL, INDEX IDX_4A8517E741DEE7B9 (token_id), INDEX IDX_4A8517E753C55F64 (map_id), PRIMARY KEY(token_id, map_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, map_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, discord_identifier VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649217BBB47 (person_id), INDEX IDX_8D93D64953C55F64 (map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE254217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE dice ADD CONSTRAINT FK_A10EE2549502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F2124387217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F21243879502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B096BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B09399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stat ADD CONSTRAINT FK_20B8FF217B185B4D FOREIGN KEY (parent_stat_id) REFERENCES stat (id)');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E741DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E753C55F64 FOREIGN KEY (map_id) REFERENCES map (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64953C55F64 FOREIGN KEY (map_id) REFERENCES map (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE254217BBB47');
        $this->addSql('ALTER TABLE dice DROP FOREIGN KEY FK_A10EE2549502F0B');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F2124387217BBB47');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F21243879502F0B');
        $this->addSql('ALTER TABLE playlist_music DROP FOREIGN KEY FK_6E4E3B096BBD148');
        $this->addSql('ALTER TABLE playlist_music DROP FOREIGN KEY FK_6E4E3B09399BBB13');
        $this->addSql('ALTER TABLE stat DROP FOREIGN KEY FK_20B8FF217B185B4D');
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E741DEE7B9');
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E753C55F64');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64953C55F64');
        $this->addSql('DROP TABLE dice');
        $this->addSql('DROP TABLE map');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE number_of_stat');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_music');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE token_map');
        $this->addSql('DROP TABLE user');
    }
}
