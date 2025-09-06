<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906100933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reading_history (id SERIAL NOT NULL, user_profile_id INT NOT NULL, story_id INT NOT NULL, chapter_link_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6323E37E6B9DD454 ON reading_history (user_profile_id)');
        $this->addSql('CREATE INDEX IDX_6323E37EAA5D4036 ON reading_history (story_id)');
        $this->addSql('CREATE INDEX IDX_6323E37ED795063E ON reading_history (chapter_link_id)');
        $this->addSql('COMMENT ON COLUMN reading_history.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reading_history ADD CONSTRAINT FK_6323E37E6B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reading_history ADD CONSTRAINT FK_6323E37EAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reading_history ADD CONSTRAINT FK_6323E37ED795063E FOREIGN KEY (chapter_link_id) REFERENCES chapter_link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reading_history DROP CONSTRAINT FK_6323E37E6B9DD454');
        $this->addSql('ALTER TABLE reading_history DROP CONSTRAINT FK_6323E37EAA5D4036');
        $this->addSql('ALTER TABLE reading_history DROP CONSTRAINT FK_6323E37ED795063E');
        $this->addSql('DROP TABLE reading_history');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
    }
}
