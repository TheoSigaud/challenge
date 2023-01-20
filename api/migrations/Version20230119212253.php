<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119212253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisement ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisement ADD zipcode VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisement ADD date_start DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisement ADD date_end DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "advertisement" DROP city');
        $this->addSql('ALTER TABLE "advertisement" DROP address');
        $this->addSql('ALTER TABLE "advertisement" DROP zipcode');
        $this->addSql('ALTER TABLE "advertisement" DROP date_start');
        $this->addSql('ALTER TABLE "advertisement" DROP date_end');
    }
}
