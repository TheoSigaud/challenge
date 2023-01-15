<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923063105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE joke_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, answer_id INT DEFAULT NULL, joke_id INT DEFAULT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CAA334807 ON comment (answer_id)');
        $this->addSql('CREATE INDEX IDX_9474526C30122C15 ON comment (joke_id)');
        $this->addSql('CREATE TABLE joke (id INT NOT NULL, author_id INT DEFAULT NULL, text TEXT NOT NULL, answer TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D8563DDF675F31B ON joke (author_id)');
        $this->addSql('CREATE TABLE joke_category (joke_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(joke_id, category_id))');
        $this->addSql('CREATE INDEX IDX_EBA09FC430122C15 ON joke_category (joke_id)');
        $this->addSql('CREATE INDEX IDX_EBA09FC412469DE2 ON joke_category (category_id)');
        $this->addSql('CREATE TABLE rate (id INT NOT NULL, joke_id INT DEFAULT NULL, star INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFEC3F3930122C15 ON rate (joke_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, author_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649F675F31B ON "user" (author_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAA334807 FOREIGN KEY (answer_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C30122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke ADD CONSTRAINT FK_8D8563DDF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT FK_EBA09FC430122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT FK_EBA09FC412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3930122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT FK_EBA09FC412469DE2');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CAA334807');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C30122C15');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT FK_EBA09FC430122C15');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT FK_DFEC3F3930122C15');
        $this->addSql('ALTER TABLE joke DROP CONSTRAINT FK_8D8563DDF675F31B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F675F31B');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE joke_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE joke');
        $this->addSql('DROP TABLE joke_category');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE "user"');
    }
}
