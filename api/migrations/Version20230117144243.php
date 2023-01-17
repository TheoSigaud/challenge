<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117144243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526c30122c15');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT fk_eba09fc430122c15');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT fk_dfec3f3930122c15');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526caa334807');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT fk_eba09fc412469de2');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE joke_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rate_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "advertisement_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "booking_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "comments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "favorites_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "advertisement" (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, photo TEXT DEFAULT NULL, properties JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95F6AEE7E3C61F9 ON "advertisement" (owner_id)');
        $this->addSql('CREATE TABLE "booking" (id INT NOT NULL, advertisement_id INT DEFAULT NULL, client_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA1FBF71B ON "booking" (advertisement_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON "booking" (client_id)');
        $this->addSql('CREATE TABLE "comments" (id INT NOT NULL, client_id INT DEFAULT NULL, advertisement_id INT DEFAULT NULL, message TEXT NOT NULL, rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962A19EB6921 ON "comments" (client_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA1FBF71B ON "comments" (advertisement_id)');
        $this->addSql('CREATE TABLE "favorites" (id INT NOT NULL, client_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E46960F519EB6921 ON "favorites" (client_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, address TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE "advertisement" ADD CONSTRAINT FK_C95F6AEE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "booking" ADD CONSTRAINT FK_E00CEDDEA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES "advertisement" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "booking" ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962AA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES "advertisement" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "favorites" ADD CONSTRAINT FK_E46960F519EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE joke');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE joke_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE rate');
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
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE joke_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE joke (id INT NOT NULL, author_id INT DEFAULT NULL, text TEXT NOT NULL, answer TEXT DEFAULT NULL, rates_total DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_8d8563ddf675f31b ON joke (author_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, answer_id INT DEFAULT NULL, joke_id INT DEFAULT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9474526caa334807 ON comment (answer_id)');
        $this->addSql('CREATE INDEX idx_9474526c30122c15 ON comment (joke_id)');
        $this->addSql('CREATE TABLE joke_category (joke_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(joke_id, category_id))');
        $this->addSql('CREATE INDEX idx_eba09fc412469de2 ON joke_category (category_id)');
        $this->addSql('CREATE INDEX idx_eba09fc430122c15 ON joke_category (joke_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rate (id INT NOT NULL, joke_id INT DEFAULT NULL, star INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dfec3f3930122c15 ON rate (joke_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526caa334807 FOREIGN KEY (answer_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526c30122c15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT fk_eba09fc430122c15 FOREIGN KEY (joke_id) REFERENCES joke (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT fk_eba09fc412469de2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT fk_dfec3f3930122c15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "advertisement"');
        $this->addSql('DROP TABLE "booking"');
        $this->addSql('DROP TABLE "comments"');
        $this->addSql('DROP TABLE "favorites"');
        $this->addSql('DROP TABLE "user"');
    }
}
