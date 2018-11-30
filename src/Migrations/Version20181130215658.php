<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181130215658 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE arc_photo.user_base_id_seq CASCADE');
        $this->addSql('CREATE TABLE arc_photo.photo (id SERIAL NOT NULL, added_by INT NOT NULL, file VARCHAR(255) NOT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DFDE75CED17F50A6 ON arc_photo.photo (uuid)');
        $this->addSql('CREATE INDEX IDX_DFDE75CE699B6BAF ON arc_photo.photo (added_by)');
        $this->addSql('COMMENT ON COLUMN arc_photo.photo.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE arc_photo.photo ADD CONSTRAINT FK_DFDE75CE699B6BAF FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE arc_photo.user_base');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE arc_photo.user_base_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE arc_photo.user_base (id SERIAL NOT NULL, added_by INT NOT NULL, file VARCHAR(255) NOT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_b58586dc699b6baf ON arc_photo.user_base (added_by)');
        $this->addSql('CREATE UNIQUE INDEX uniq_b58586dcd17f50a6 ON arc_photo.user_base (uuid)');
        $this->addSql('COMMENT ON COLUMN arc_photo.user_base.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE arc_photo.user_base ADD CONSTRAINT fk_b58586dc699b6baf FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE arc_photo.photo');
    }
}
