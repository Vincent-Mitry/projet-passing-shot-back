<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622150103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blocked_court (id INT AUTO_INCREMENT NOT NULL, court_id INT NOT NULL, user_id INT DEFAULT NULL, start_datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', blocked_reason VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D4EF58C3E3184009 (court_id), INDEX IDX_D4EF58C3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, starting_time TIME NOT NULL, ending_time TIME NOT NULL, name VARCHAR(200) NOT NULL, logo VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_B8EE3872A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE court (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, surface_id INT NOT NULL, name VARCHAR(64) NOT NULL, lightning TINYINT(1) NOT NULL, indoor TINYINT(1) NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, picture VARCHAR(255) DEFAULT NULL, detailed_map VARCHAR(255) DEFAULT NULL, rating_avg NUMERIC(2, 1) DEFAULT NULL, slug VARCHAR(255) NOT NULL, renovated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', rating_count INT DEFAULT NULL, INDEX IDX_63AE193F61190A32 (club_id), INDEX IDX_63AE193FCA11F534 (surface_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, court_id INT NOT NULL, user_id INT NOT NULL, player2_id INT DEFAULT NULL, player3_id INT DEFAULT NULL, player4_id INT DEFAULT NULL, start_datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status TINYINT(1) NOT NULL, score VARCHAR(19) DEFAULT NULL, court_rating INT DEFAULT NULL, count_players SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_42C84955E3184009 (court_id), INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955D22CABCD (player2_id), INDEX IDX_42C849556A90CCA8 (player3_id), INDEX IDX_42C84955F747F411 (player4_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE surface (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, gender_id INT NOT NULL, lastname VARCHAR(64) NOT NULL, firstname VARCHAR(64) NOT NULL, email VARCHAR(180) NOT NULL, level VARCHAR(64) NOT NULL, phone VARCHAR(10) NOT NULL, password VARCHAR(100) NOT NULL, picture VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', birthdate DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8D93D649708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blocked_court ADD CONSTRAINT FK_D4EF58C3E3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('ALTER TABLE blocked_court ADD CONSTRAINT FK_D4EF58C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE court ADD CONSTRAINT FK_63AE193FCA11F534 FOREIGN KEY (surface_id) REFERENCES surface (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D22CABCD FOREIGN KEY (player2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556A90CCA8 FOREIGN KEY (player3_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F747F411 FOREIGN KEY (player4_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193F61190A32');
        $this->addSql('ALTER TABLE blocked_court DROP FOREIGN KEY FK_D4EF58C3E3184009');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E3184009');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649708A0E0');
        $this->addSql('ALTER TABLE court DROP FOREIGN KEY FK_63AE193FCA11F534');
        $this->addSql('ALTER TABLE blocked_court DROP FOREIGN KEY FK_D4EF58C3A76ED395');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D22CABCD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556A90CCA8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F747F411');
        $this->addSql('DROP TABLE blocked_court');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE court');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE surface');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
