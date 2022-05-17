<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517195617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, depart_date DATETIME NOT NULL, return_date DATETIME NOT NULL, return_vehicle TINYINT(1) DEFAULT NULL, return_key TINYINT(1) DEFAULT NULL, destination_address VARCHAR(128) NOT NULL, driver INT NOT NULL, affected_vehicle INT NOT NULL, INDEX fk_loan_affected_vehicle (affected_vehicle), INDEX fk_loan_driver (driver), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passenger (id_loan INT NOT NULL, id_person INT NOT NULL, INDEX fk_passenger_id_person (id_person), PRIMARY KEY(id_loan, id_person)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) NOT NULL, address VARCHAR(128) NOT NULL, age INT NOT NULL, phone_number INT DEFAULT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(32) NOT NULL, model VARCHAR(32) NOT NULL, nb_places INT NOT NULL, id_site INT NOT NULL, INDEX fk_vehicle_id_site (id_site), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_key (id INT AUTO_INCREMENT NOT NULL, id_vehicle INT DEFAULT NULL, INDEX fk_key_id_vehicle (id_vehicle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE passenger');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_key');
    }
}
