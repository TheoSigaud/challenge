<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211103248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "advertisement_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "booking_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "comments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "favorites_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "advertisement" (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, photo TEXT DEFAULT NULL, properties JSON DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(5) DEFAULT NULL, date_start DATE DEFAULT NULL, date_end DATE DEFAULT NULL, status BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95F6AEE7E3C61F9 ON "advertisement" (owner_id)');
        $this->addSql('CREATE TABLE "booking" (id INT NOT NULL, advertisement_id INT DEFAULT NULL, client_id INT DEFAULT NULL, date_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, date_end DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payment VARCHAR(255) NOT NULL, cancel_user TEXT DEFAULT NULL, cancel_host TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA1FBF71B ON "booking" (advertisement_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON "booking" (client_id)');
        $this->addSql('COMMENT ON COLUMN "booking".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "comments" (id INT NOT NULL, client_id INT DEFAULT NULL, advertisement_id INT DEFAULT NULL, message TEXT NOT NULL, rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962A19EB6921 ON "comments" (client_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA1FBF71B ON "comments" (advertisement_id)');
        $this->addSql('CREATE TABLE "favorites" (id INT NOT NULL, client_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E46960F519EB6921 ON "favorites" (client_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, address TEXT NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE "advertisement" ADD CONSTRAINT FK_C95F6AEE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "booking" ADD CONSTRAINT FK_E00CEDDEA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES "advertisement" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "booking" ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962AA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES "advertisement" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "favorites" ADD CONSTRAINT FK_E46960F519EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "booking" DROP CONSTRAINT FK_E00CEDDEA1FBF71B');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962AA1FBF71B');
        $this->addSql('ALTER TABLE "advertisement" DROP CONSTRAINT FK_C95F6AEE7E3C61F9');
        $this->addSql('ALTER TABLE "booking" DROP CONSTRAINT FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A19EB6921');
        $this->addSql('ALTER TABLE "favorites" DROP CONSTRAINT FK_E46960F519EB6921');
        $this->addSql('DROP SEQUENCE "advertisement_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "booking_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "comments_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "favorites_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "advertisement"');
        $this->addSql('DROP TABLE "booking"');
        $this->addSql('DROP TABLE "comments"');
        $this->addSql('DROP TABLE "favorites"');
        $this->addSql('DROP TABLE "user"');
    }
}
