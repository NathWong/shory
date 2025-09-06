<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906065225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
        $this->addSql('ALTER TABLE story ADD beginning_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE story ALTER story_status DROP NOT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB5604387524E763 FOREIGN KEY (beginning_id) REFERENCES chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB5604387524E763 ON story (beginning_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT FK_EB5604387524E763');
        $this->addSql('DROP INDEX UNIQ_EB5604387524E763');
        $this->addSql('ALTER TABLE story DROP beginning_id');
        $this->addSql('ALTER TABLE story ALTER story_status SET NOT NULL');
    }
}
