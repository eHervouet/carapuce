<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721141355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, age INT NOT NULL, UNIQUE INDEX UNIQ_34DCD176E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03C3423909 FOREIGN KEY (driver_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03DC8F6CF2 FOREIGN KEY (affected_vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE passenger ADD CONSTRAINT FK_3BEFE8DD10EE2FFF FOREIGN KEY (id_loan_id) REFERENCES loan (id)');
        $this->addSql('ALTER TABLE passenger ADD CONSTRAINT FK_3BEFE8DDA14E0760 FOREIGN KEY (id_person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4862820BF36 FOREIGN KEY (id_site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE vehicle_key ADD CONSTRAINT FK_10C4726FF1D99706 FOREIGN KEY (id_vehicle_id) REFERENCES vehicle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03C3423909');
        $this->addSql('ALTER TABLE passenger DROP FOREIGN KEY FK_3BEFE8DDA14E0760');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03DC8F6CF2');
        $this->addSql('ALTER TABLE passenger DROP FOREIGN KEY FK_3BEFE8DD10EE2FFF');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4862820BF36');
        $this->addSql('ALTER TABLE vehicle_key DROP FOREIGN KEY FK_10C4726FF1D99706');
    }
}
