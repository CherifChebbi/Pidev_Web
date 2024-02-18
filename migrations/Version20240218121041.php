<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218121041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monument ADD id_ville INT DEFAULT NULL');
        $this->addSql('ALTER TABLE monument ADD CONSTRAINT FK_7BB88283AD4698F3 FOREIGN KEY (id_ville) REFERENCES ville (id_ville)');
        $this->addSql('CREATE INDEX IDX_7BB88283AD4698F3 ON monument (id_ville)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monument DROP FOREIGN KEY FK_7BB88283AD4698F3');
        $this->addSql('DROP INDEX IDX_7BB88283AD4698F3 ON monument');
        $this->addSql('ALTER TABLE monument DROP id_ville');
    }
}
