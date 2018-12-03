<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203095624 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA arc_photo');
        $this->addSql('CREATE SCHEMA arc_configuration_list');
        $this->addSql('CREATE SCHEMA arc_main');
        $this->addSql('CREATE TABLE arc_photo.photo (id SERIAL NOT NULL, added_by INT NOT NULL, file VARCHAR(255) NOT NULL, coordinates Geography(Point) DEFAULT NULL, metadata JSONB DEFAULT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DFDE75CED17F50A6 ON arc_photo.photo (uuid)');
        $this->addSql('CREATE INDEX IDX_DFDE75CE699B6BAF ON arc_photo.photo (added_by)');
        $this->addSql('COMMENT ON COLUMN arc_photo.photo.coordinates IS \'(DC2Type:point_geog)\'');
        $this->addSql('COMMENT ON COLUMN arc_photo.photo.metadata IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN arc_photo.photo.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE arc_photo.element (id SERIAL NOT NULL, element_id INT DEFAULT NULL, condition_id INT DEFAULT NULL, photo_id INT NOT NULL, added_by INT NOT NULL, sector Geometry(Polygon) DEFAULT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C983543D17F50A6 ON arc_photo.element (uuid)');
        $this->addSql('CREATE INDEX IDX_5C9835431F1F2A24 ON arc_photo.element (element_id)');
        $this->addSql('CREATE INDEX IDX_5C983543887793B6 ON arc_photo.element (condition_id)');
        $this->addSql('CREATE INDEX IDX_5C9835437E9E4C8C ON arc_photo.element (photo_id)');
        $this->addSql('CREATE INDEX IDX_5C983543699B6BAF ON arc_photo.element (added_by)');
        $this->addSql('COMMENT ON COLUMN arc_photo.element.sector IS \'(DC2Type:polygon_geom)\'');
        $this->addSql('COMMENT ON COLUMN arc_photo.element.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE arc_configuration_list.condition (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47FAB1DED17F50A6 ON arc_configuration_list.condition (uuid)');
        $this->addSql('CREATE TABLE arc_configuration_list.element (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, primary_color VARCHAR(7) DEFAULT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9CD9FFCED17F50A6 ON arc_configuration_list.element (uuid)');
        $this->addSql('CREATE TABLE arc_main.user_base (id SERIAL NOT NULL, username VARCHAR(255) NOT NULL, roles VARCHAR(250) NOT NULL, last_login DATE DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472173F9F85E0677 ON arc_main.user_base (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472173F9E7927C74 ON arc_main.user_base (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472173F9D17F50A6 ON arc_main.user_base (uuid)');
        $this->addSql('COMMENT ON COLUMN arc_main.user_base.last_login IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE arc_photo.photo ADD CONSTRAINT FK_DFDE75CE699B6BAF FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C9835431F1F2A24 FOREIGN KEY (element_id) REFERENCES arc_configuration_list.element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C983543887793B6 FOREIGN KEY (condition_id) REFERENCES arc_configuration_list.condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C9835437E9E4C8C FOREIGN KEY (photo_id) REFERENCES arc_photo.photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C983543699B6BAF FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C9835437E9E4C8C');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C983543887793B6');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C9835431F1F2A24');
        $this->addSql('ALTER TABLE arc_photo.photo DROP CONSTRAINT FK_DFDE75CE699B6BAF');
        $this->addSql('ALTER TABLE arc_photo.element DROP CONSTRAINT FK_5C983543699B6BAF');
        $this->addSql('DROP TABLE arc_photo.photo');
        $this->addSql('DROP TABLE arc_photo.element');
        $this->addSql('DROP TABLE arc_configuration_list.condition');
        $this->addSql('DROP TABLE arc_configuration_list.element');
        $this->addSql('DROP TABLE arc_main.user_base');
    }
}
