<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228173458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD restauran_id INT DEFAULT NULL, DROP restaurant_id');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC20467EB0 FOREIGN KEY (restauran_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC20467EB0 ON commentaire (restauran_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC20467EB0');
        $this->addSql('DROP INDEX IDX_67F068BC20467EB0 ON commentaire');
        $this->addSql('ALTER TABLE commentaire ADD restaurant_id INT NOT NULL, DROP restauran_id');
    }
}
