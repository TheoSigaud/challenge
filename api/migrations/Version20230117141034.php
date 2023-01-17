<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117141034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526ca1fbf71b');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT fk_e00ceddea1fbf71b');
        $this->addSql('DROP SEQUENCE advertisement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, author_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649F675F31B ON "user" (author_id)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE booking');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F675F31B');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE advertisement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisement (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description TEXT NOT NULL, photos VARCHAR(255) DEFAULT NULL, properties JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c95f6aee7e3c61f9 ON advertisement (owner_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, advertisement_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, message TEXT NOT NULL, rate DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9474526c79f37ae5 ON comment (id_user_id)');
        $this->addSql('CREATE INDEX idx_9474526ca1fbf71b ON comment (advertisement_id)');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, advertisement_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, payment_intent TEXT DEFAULT NULL, client_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e00ceddea1fbf71b ON booking (advertisement_id)');
        $this->addSql('CREATE INDEX idx_e00cedde19eb6921 ON booking (client_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526ca1fbf71b FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT fk_e00ceddea1fbf71b FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "user"');
    }
}
