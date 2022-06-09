<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609101131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blocked_court ADD court_id INT NOT NULL');
        $this->addSql('ALTER TABLE blocked_court ADD CONSTRAINT FK_D4EF58C3E3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('CREATE INDEX IDX_D4EF58C3E3184009 ON blocked_court (court_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blocked_court DROP FOREIGN KEY FK_D4EF58C3E3184009');
        $this->addSql('DROP INDEX IDX_D4EF58C3E3184009 ON blocked_court');
        $this->addSql('ALTER TABLE blocked_court DROP court_id');
    }
}
