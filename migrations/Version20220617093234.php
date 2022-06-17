<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617093234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD player2_id INT DEFAULT NULL, ADD player3_id INT DEFAULT NULL, ADD player4_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D22CABCD FOREIGN KEY (player2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556A90CCA8 FOREIGN KEY (player3_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F747F411 FOREIGN KEY (player4_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C84955D22CABCD ON reservation (player2_id)');
        $this->addSql('CREATE INDEX IDX_42C849556A90CCA8 ON reservation (player3_id)');
        $this->addSql('CREATE INDEX IDX_42C84955F747F411 ON reservation (player4_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D22CABCD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556A90CCA8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F747F411');
        $this->addSql('DROP INDEX IDX_42C84955D22CABCD ON reservation');
        $this->addSql('DROP INDEX IDX_42C849556A90CCA8 ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955F747F411 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP player2_id, DROP player3_id, DROP player4_id');
    }
}
