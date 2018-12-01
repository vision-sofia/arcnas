<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201150329 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE arc_photo.element ADD photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C9835437E9E4C8C FOREIGN KEY (photo_id) REFERENCES arc_photo.photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5C9835437E9E4C8C ON arc_photo.element (photo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C9835437E9E4C8C');
        $this->addSql('DROP INDEX IDX_5C9835437E9E4C8C');
        $this->addSql('ALTER TABLE arc_photo.element DROP photo_id');
    }
}
