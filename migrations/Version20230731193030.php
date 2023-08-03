<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731193030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE token_user (token_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EF97E32B41DEE7B9 (token_id), INDEX IDX_EF97E32BA76ED395 (user_id), PRIMARY KEY(token_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token_user ADD CONSTRAINT FK_EF97E32B41DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_user ADD CONSTRAINT FK_EF97E32BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E741DEE7B9');
        $this->addSql('ALTER TABLE token_map DROP FOREIGN KEY FK_4A8517E753C55F64');
        $this->addSql('DROP TABLE token_map');
        $this->addSql('ALTER TABLE token ADD map_id INT NOT NULL');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B53C55F64 FOREIGN KEY (map_id) REFERENCES map (id)');
        $this->addSql('CREATE INDEX IDX_5F37A13B53C55F64 ON token (map_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE token_map (token_id INT NOT NULL, map_id INT NOT NULL, INDEX IDX_4A8517E753C55F64 (map_id), INDEX IDX_4A8517E741DEE7B9 (token_id), PRIMARY KEY(token_id, map_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E741DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_map ADD CONSTRAINT FK_4A8517E753C55F64 FOREIGN KEY (map_id) REFERENCES map (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_user DROP FOREIGN KEY FK_EF97E32B41DEE7B9');
        $this->addSql('ALTER TABLE token_user DROP FOREIGN KEY FK_EF97E32BA76ED395');
        $this->addSql('DROP TABLE token_user');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B53C55F64');
        $this->addSql('DROP INDEX IDX_5F37A13B53C55F64 ON token');
        $this->addSql('ALTER TABLE token DROP map_id');
    }
}
