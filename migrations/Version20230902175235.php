<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902175235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_world_parameter DROP FOREIGN KEY FK_1167D4868925311C');
        $this->addSql('ALTER TABLE user_world_parameter DROP FOREIGN KEY FK_1167D486A76ED395');
        $this->addSql('DROP TABLE user_world_parameter');
        $this->addSql('ALTER TABLE attribute ADD acronym VARCHAR(3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_world_parameter (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, world_id INT NOT NULL, music_volume INT NOT NULL, INDEX IDX_1167D486A76ED395 (user_id), INDEX IDX_1167D4868925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_world_parameter ADD CONSTRAINT FK_1167D4868925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE user_world_parameter ADD CONSTRAINT FK_1167D486A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attribute DROP acronym');
    }
}
