<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201014518 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE arc_photo.element ADD condition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C983543887793B6 FOREIGN KEY (condition_id) REFERENCES configuration_list.condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5C983543887793B6 ON arc_photo.element (condition_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C983543887793B6');
        $this->addSql('DROP INDEX IDX_5C983543887793B6');
        $this->addSql('ALTER TABLE arc_photo.element DROP condition_id');
    }
}
