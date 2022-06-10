<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610150945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE court ADD slug VARCHAR(255) NOT NULL, CHANGE picture picture VARCHAR(255) DEFAULT NULL, CHANGE detailled_map detailled_map VARCHAR(255) DEFAULT NULL, CHANGE rating_avg rating_avg NUMERIC(2, 1) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE status status SMALLINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE gender gender SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE court DROP slug, CHANGE picture picture VARCHAR(255) NOT NULL, CHANGE detailled_map detailled_map VARCHAR(255) NOT NULL, CHANGE rating_avg rating_avg NUMERIC(3, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE status status SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE gender gender VARCHAR(50) NOT NULL');
    }
}
