<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906185336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contributor (id SERIAL NOT NULL, user_profile_id INT NOT NULL, story_group_id INT NOT NULL, role VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA6F97936B9DD454 ON contributor (user_profile_id)');
        $this->addSql('CREATE INDEX IDX_DA6F9793FDB1C7DB ON contributor (story_group_id)');
        $this->addSql('CREATE TABLE story_group (id SERIAL NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, genre VARCHAR(255) DEFAULT NULL, template VARCHAR(50) DEFAULT \'default\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B180E4667E3C61F9 ON story_group (owner_id)');
        $this->addSql('COMMENT ON COLUMN story_group.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN story_group.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE contributor ADD CONSTRAINT FK_DA6F97936B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contributor ADD CONSTRAINT FK_DA6F9793FDB1C7DB FOREIGN KEY (story_group_id) REFERENCES story_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story_group ADD CONSTRAINT FK_B180E4667E3C61F9 FOREIGN KEY (owner_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story_user_profile DROP CONSTRAINT fk_827432706b9dd454');
        $this->addSql('ALTER TABLE story_user_profile DROP CONSTRAINT fk_82743270aa5d4036');
        $this->addSql('DROP TABLE story_user_profile');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT fk_eb5604387e3c61f9');
        $this->addSql('DROP INDEX idx_eb5604387e3c61f9');
        $this->addSql('ALTER TABLE story ADD story_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE story ADD published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE story DROP owner_id');
        $this->addSql('ALTER TABLE story DROP title');
        $this->addSql('COMMENT ON COLUMN story.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438FDB1C7DB FOREIGN KEY (story_group_id) REFERENCES story_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EB560438FDB1C7DB ON story (story_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT FK_EB560438FDB1C7DB');
        $this->addSql('CREATE TABLE story_user_profile (story_id INT NOT NULL, user_profile_id INT NOT NULL, PRIMARY KEY(story_id, user_profile_id))');
        $this->addSql('CREATE INDEX idx_827432706b9dd454 ON story_user_profile (user_profile_id)');
        $this->addSql('CREATE INDEX idx_82743270aa5d4036 ON story_user_profile (story_id)');
        $this->addSql('ALTER TABLE story_user_profile ADD CONSTRAINT fk_827432706b9dd454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE story_user_profile ADD CONSTRAINT fk_82743270aa5d4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contributor DROP CONSTRAINT FK_DA6F97936B9DD454');
        $this->addSql('ALTER TABLE contributor DROP CONSTRAINT FK_DA6F9793FDB1C7DB');
        $this->addSql('ALTER TABLE story_group DROP CONSTRAINT FK_B180E4667E3C61F9');
        $this->addSql('DROP TABLE contributor');
        $this->addSql('DROP TABLE story_group');
        $this->addSql('DROP INDEX IDX_EB560438FDB1C7DB');
        $this->addSql('ALTER TABLE story ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE story ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE story DROP story_group_id');
        $this->addSql('ALTER TABLE story DROP published_at');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT fk_eb5604387e3c61f9 FOREIGN KEY (owner_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_eb5604387e3c61f9 ON story (owner_id)');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
    }
}
