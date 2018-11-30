<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181130215152 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA arc_photo');
        $this->addSql('CREATE TABLE arc_photo.user_base (id SERIAL NOT NULL, added_by INT NOT NULL, file VARCHAR(255) NOT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B58586DCD17F50A6 ON arc_photo.user_base (uuid)');
        $this->addSql('CREATE INDEX IDX_B58586DC699B6BAF ON arc_photo.user_base (added_by)');
        $this->addSql('COMMENT ON COLUMN arc_photo.user_base.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE arc_photo.user_base ADD CONSTRAINT FK_B58586DC699B6BAF FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE arc_photo.user_base');
    }
}
