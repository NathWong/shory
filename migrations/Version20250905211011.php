<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905211011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE moderation_message (id SERIAL NOT NULL, story_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9DFEFBE0AA5D4036 ON moderation_message (story_id)');
        $this->addSql('CREATE INDEX IDX_9DFEFBE0F624B39D ON moderation_message (sender_id)');
        $this->addSql('CREATE INDEX IDX_9DFEFBE0CD53EDB6 ON moderation_message (receiver_id)');
        $this->addSql('COMMENT ON COLUMN moderation_message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE moderation_message ADD CONSTRAINT FK_9DFEFBE0AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moderation_message ADD CONSTRAINT FK_9DFEFBE0F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moderation_message ADD CONSTRAINT FK_9DFEFBE0CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
        $this->addSql('ALTER TABLE story ALTER template SET DEFAULT \'default\'');
        $this->addSql('ALTER TABLE story ALTER template TYPE VARCHAR(50)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE moderation_message DROP CONSTRAINT FK_9DFEFBE0AA5D4036');
        $this->addSql('ALTER TABLE moderation_message DROP CONSTRAINT FK_9DFEFBE0F624B39D');
        $this->addSql('ALTER TABLE moderation_message DROP CONSTRAINT FK_9DFEFBE0CD53EDB6');
        $this->addSql('DROP TABLE moderation_message');
        $this->addSql('ALTER TABLE chapter_link ALTER responses TYPE JSON');
        $this->addSql('ALTER TABLE story ALTER template DROP DEFAULT');
        $this->addSql('ALTER TABLE story ALTER template TYPE VARCHAR(255)');
    }
}
