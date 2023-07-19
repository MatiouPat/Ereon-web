<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719224651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, view VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, top_position INT NOT NULL, left_position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_map (token_id INT NOT NULL, map_id INT NOT NULL, INDEX IDX_4A8517E741DEE7B9 (token_id), INDEX IDX_4A8517E753C55F64 (map_id), PRIMARY KEY(token_id, map_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E741DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E753C55F64 FOREIGN KEY (map_id) REFERENCES map (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E741DEE7B9');
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E753C55F64');
        $this->addSql('DROP TABLE map');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE token_map');
    }
}
