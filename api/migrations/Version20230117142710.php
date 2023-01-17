<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117142710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD advertisement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES "advertisement" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F9E962A19EB6921 ON comments (client_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA1FBF71B ON comments (advertisement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A19EB6921');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962AA1FBF71B');
        $this->addSql('DROP INDEX IDX_5F9E962A19EB6921');
        $this->addSql('DROP INDEX IDX_5F9E962AA1FBF71B');
        $this->addSql('ALTER TABLE "comments" DROP client_id');
        $this->addSql('ALTER TABLE "comments" DROP advertisement_id');
    }
}
