<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720101406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, affected_vehicle_id INT NOT NULL, depart_date DATETIME NOT NULL, return_date DATETIME NOT NULL, return_vehicle TINYINT(1) NOT NULL, return_key TINYINT(1) NOT NULL, destination_address VARCHAR(128) NOT NULL, INDEX IDX_C5D30D03C3423909 (driver_id), INDEX IDX_C5D30D03DC8F6CF2 (affected_vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passenger (id_loan_id INT NOT NULL, id_person_id INT NOT NULL, INDEX IDX_3BEFE8DD10EE2FFF (id_loan_id), INDEX IDX_3BEFE8DDA14E0760 (id_person_id), PRIMARY KEY(id_loan_id, id_person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, id_site_id INT NOT NULL, brand VARCHAR(32) NOT NULL, model VARCHAR(32) NOT NULL, nb_places INT NOT NULL, INDEX IDX_1B80E4862820BF36 (id_site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_key (id INT AUTO_INCREMENT NOT NULL, id_vehicle_id INT NOT NULL, INDEX IDX_10C4726FF1D99706 (id_vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03C3423909 FOREIGN KEY (driver_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03DC8F6CF2 FOREIGN KEY (affected_vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE passenger ADD CONSTRAINT FK_3BEFE8DD10EE2FFF FOREIGN KEY (id_loan_id) REFERENCES loan (id)');
        $this->addSql('ALTER TABLE passenger ADD CONSTRAINT FK_3BEFE8DDA14E0760 FOREIGN KEY (id_person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4862820BF36 FOREIGN KEY (id_site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE vehicle_key ADD CONSTRAINT FK_10C4726FF1D99706 FOREIGN KEY (id_vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('DROP TABLE personne');
        $this->addSql('ALTER TABLE person ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, ADD age INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE passenger DROP FOREIGN KEY FK_3BEFE8DD10EE2FFF');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4862820BF36');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03DC8F6CF2');
        $this->addSql('ALTER TABLE vehicle_key DROP FOREIGN KEY FK_10C4726FF1D99706');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_FCEC9EFE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE passenger');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_key');
        $this->addSql('ALTER TABLE person DROP first_name, DROP last_name, DROP address, DROP phone_number, DROP age');
    }
}
