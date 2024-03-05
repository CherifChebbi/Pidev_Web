<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305180300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monument (id_monument INT AUTO_INCREMENT NOT NULL, id_ville INT DEFAULT NULL, nom_monument VARCHAR(30) NOT NULL, img_monument VARCHAR(50) NOT NULL, desc_monument LONGTEXT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_7BB88283AD4698F3 (id_ville), PRIMARY KEY(id_monument)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id_pays INT AUTO_INCREMENT NOT NULL, nom_pays VARCHAR(30) NOT NULL, img_pays VARCHAR(30) NOT NULL, desc_pays LONGTEXT NOT NULL, langue VARCHAR(255) NOT NULL, continent VARCHAR(255) NOT NULL, nb_villes INT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_pays)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id_ville INT AUTO_INCREMENT NOT NULL, id_pays INT DEFAULT NULL, nom_ville VARCHAR(30) NOT NULL, img_ville VARCHAR(50) NOT NULL, desc_ville LONGTEXT NOT NULL, nb_monuments INT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_43C3D9C3BFBF20AC (id_pays), PRIMARY KEY(id_ville)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monument ADD CONSTRAINT FK_7BB88283AD4698F3 FOREIGN KEY (id_ville) REFERENCES ville (id_ville)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3BFBF20AC FOREIGN KEY (id_pays) REFERENCES pays (id_pays)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monument DROP FOREIGN KEY FK_7BB88283AD4698F3');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3BFBF20AC');
        $this->addSql('DROP TABLE monument');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
