<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517200423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX fk_passenger_id_person ON passenger');
        $this->addSql('CREATE INDEX fk_passenger_id_loan ON passenger (id_loan)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX fk_passenger_id_loan ON passenger');
        $this->addSql('CREATE INDEX fk_passenger_id_person ON passenger (id_person)');
    }
}
