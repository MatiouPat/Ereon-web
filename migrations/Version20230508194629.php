<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508194629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE number_of_stat (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, stat_id INT NOT NULL, value INT NOT NULL, INDEX IDX_F2124387217BBB47 (person_id), INDEX IDX_F21243879502F0B (stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F2124387217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE number_of_stat ADD CONSTRAINT FK_F21243879502F0B FOREIGN KEY (stat_id) REFERENCES stat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F2124387217BBB47');
        $this->addSql('ALTER TABLE number_of_stat DROP FOREIGN KEY FK_F21243879502F0B');
        $this->addSql('DROP TABLE number_of_stat');
    }
}
