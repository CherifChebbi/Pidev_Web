<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306181107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, id_category_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, lieu VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, image_event VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7A545015 (id_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_event (id INT AUTO_INCREMENT NOT NULL, id_event_id INT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, num_tel INT NOT NULL, date_reservation DATE NOT NULL, INDEX IDX_78D1DA00212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A545015 FOREIGN KEY (id_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA00212C041E FOREIGN KEY (id_event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A545015');
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA00212C041E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE reservation_event');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
    }
}
