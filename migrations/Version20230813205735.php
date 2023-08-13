<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813205735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE music_player (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, current_music_id INT DEFAULT NULL, is_playing TINYINT(1) NOT NULL, is_looping TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_DC1B99C8925311C (world_id), INDEX IDX_DC1B99CA4E3C139 (current_music_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_parameter (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, is_dark_theme TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2A771CF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_world_parameter (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, world_id INT NOT NULL, music_volume INT NOT NULL, INDEX IDX_1167D486A76ED395 (user_id), INDEX IDX_1167D4868925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE music_player ADD CONSTRAINT FK_DC1B99C8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE music_player ADD CONSTRAINT FK_DC1B99CA4E3C139 FOREIGN KEY (current_music_id) REFERENCES music (id)');
        $this->addSql('ALTER TABLE user_parameter ADD CONSTRAINT FK_2A771CF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_world_parameter ADD CONSTRAINT FK_1167D486A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_world_parameter ADD CONSTRAINT FK_1167D4868925311C FOREIGN KEY (world_id) REFERENCES world (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE music_player DROP FOREIGN KEY FK_DC1B99C8925311C');
        $this->addSql('ALTER TABLE music_player DROP FOREIGN KEY FK_DC1B99CA4E3C139');
        $this->addSql('ALTER TABLE user_parameter DROP FOREIGN KEY FK_2A771CF4A76ED395');
        $this->addSql('ALTER TABLE user_world_parameter DROP FOREIGN KEY FK_1167D486A76ED395');
        $this->addSql('ALTER TABLE user_world_parameter DROP FOREIGN KEY FK_1167D4868925311C');
        $this->addSql('DROP TABLE music_player');
        $this->addSql('DROP TABLE user_parameter');
        $this->addSql('DROP TABLE user_world_parameter');
    }
}
