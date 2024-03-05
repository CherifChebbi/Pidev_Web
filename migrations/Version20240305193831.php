<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305193831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_restaurant DROP FOREIGN KEY FK_4CF2D4D3A76ED395');
        $this->addSql('ALTER TABLE user_restaurant DROP FOREIGN KEY FK_4CF2D4D3B1E7706E');
        $this->addSql('DROP TABLE user_restaurant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_restaurant (user_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_4CF2D4D3A76ED395 (user_id), INDEX IDX_4CF2D4D3B1E7706E (restaurant_id), PRIMARY KEY(user_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_restaurant ADD CONSTRAINT FK_4CF2D4D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_restaurant ADD CONSTRAINT FK_4CF2D4D3B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
    }
}
