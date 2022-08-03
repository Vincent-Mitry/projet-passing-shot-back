<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616191312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE surface (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE court ADD surface_id INT DEFAULT NULL, DROP surface');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193FCA11F534 FOREIGN KEY (surface_id) REFERENCES surface (id)');
        $this->addSql('CREATE INDEX IDX_63AE193FCA11F534 ON court (surface_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193FCA11F534');
        $this->addSql('DROP TABLE surface');
        $this->addSql('DROP INDEX IDX_63AE193FCA11F534 ON court');
        $this->addSql('ALTER TABLE court ADD surface SMALLINT NOT NULL, DROP surface_id');
    }
}
