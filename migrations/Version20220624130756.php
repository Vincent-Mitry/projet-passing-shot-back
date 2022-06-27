<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624130756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193F61190A32');
        $this->addSql('ALTER TABLE court CHANGE club_id club_id INT NOT NULL');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193F61190A32');
        $this->addSql('ALTER TABLE court CHANGE club_id club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE SET NULL');
    }
}
