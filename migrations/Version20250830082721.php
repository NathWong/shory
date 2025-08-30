<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830082721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapter_link (id SERIAL NOT NULL, target_id INT DEFAULT NULL, source_id INT NOT NULL, responses JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1A5DED64158E0B66 ON chapter_link (target_id)');
        $this->addSql('CREATE INDEX IDX_1A5DED64953C1C61 ON chapter_link (source_id)');
        $this->addSql('ALTER TABLE chapter_link ADD CONSTRAINT FK_1A5DED64158E0B66 FOREIGN KEY (target_id) REFERENCES chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chapter_link ADD CONSTRAINT FK_1A5DED64953C1C61 FOREIGN KEY (source_id) REFERENCES chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chapter ADD link_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chapter_link DROP CONSTRAINT FK_1A5DED64158E0B66');
        $this->addSql('ALTER TABLE chapter_link DROP CONSTRAINT FK_1A5DED64953C1C61');
        $this->addSql('DROP TABLE chapter_link');
        $this->addSql('ALTER TABLE chapter DROP link_type');
    }
}
