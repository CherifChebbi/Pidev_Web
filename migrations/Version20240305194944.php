<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305194944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` ADD restaurant_id INT DEFAULT NULL, ADD liked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3B1E7706E ON `like` (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3B1E7706E');
        $this->addSql('DROP INDEX IDX_AC6340B3B1E7706E ON `like`');
        $this->addSql('ALTER TABLE `like` DROP restaurant_id, DROP liked');
    }
}
