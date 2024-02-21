<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218220758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD id_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A545015 FOREIGN KEY (id_category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A545015 ON event (id_category_id)');
        $this->addSql('ALTER TABLE reservation_event ADD id_event_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA00212C041E FOREIGN KEY (id_event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_78D1DA00212C041E ON reservation_event (id_event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A545015');
        $this->addSql('DROP INDEX IDX_3BAE0AA7A545015 ON event');
        $this->addSql('ALTER TABLE event DROP id_category_id');
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA00212C041E');
        $this->addSql('DROP INDEX IDX_78D1DA00212C041E ON reservation_event');
        $this->addSql('ALTER TABLE reservation_event DROP id_event_id');
    }
}
