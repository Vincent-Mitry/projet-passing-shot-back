<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624152423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193FCA11F534');
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193F61190A32');
        $this->addSql('ALTER TABLE court CHANGE club_id club_id INT NOT NULL, CHANGE surface_id surface_id INT NOT NULL');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193FCA11F534 FOREIGN KEY (surface_id) REFERENCES surface (id)');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E3184009');
        $this->addSql('ALTER TABLE reservation CHANGE court_id court_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64961190A32');
        $this->addSql('DROP INDEX UNIQ_8D93D64961190A32 ON user');
        $this->addSql('ALTER TABLE user DROP club_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193F61190A32');
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193FCA11F534');
        $this->addSql('ALTER TABLE court CHANGE club_id club_id INT DEFAULT NULL, CHANGE surface_id surface_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193FCA11F534 FOREIGN KEY (surface_id) REFERENCES surface (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E3184009');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation CHANGE court_id court_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E3184009 FOREIGN KEY (court_id) REFERENCES court (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64961190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64961190A32 ON user (club_id)');
    }
}
