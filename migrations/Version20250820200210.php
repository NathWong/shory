<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250820200210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE story (id SERIAL NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, summary TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, story_status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB5604387E3C61F9 ON story (owner_id)');
        $this->addSql('COMMENT ON COLUMN story.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN story.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE story_user_profile (story_id INT NOT NULL, user_profile_id INT NOT NULL, PRIMARY KEY(story_id, user_profile_id))');
        $this->addSql('CREATE INDEX IDX_82743270AA5D4036 ON story_user_profile (story_id)');
        $this->addSql('CREATE INDEX IDX_827432706B9DD454 ON story_user_profile (user_profile_id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB5604387E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story_user_profile ADD CONSTRAINT FK_82743270AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story_user_profile ADD CONSTRAINT FK_827432706B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT FK_EB5604387E3C61F9');
        $this->addSql('ALTER TABLE story_user_profile DROP CONSTRAINT FK_82743270AA5D4036');
        $this->addSql('ALTER TABLE story_user_profile DROP CONSTRAINT FK_827432706B9DD454');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE story_user_profile');
    }
}
