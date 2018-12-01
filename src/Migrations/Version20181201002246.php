<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201002246 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE arc_photo.element (id SERIAL NOT NULL, element_id INT DEFAULT NULL, added_by INT NOT NULL, a_x DOUBLE PRECISION NOT NULL, a_y DOUBLE PRECISION NOT NULL, b_x DOUBLE PRECISION NOT NULL, b_y DOUBLE PRECISION NOT NULL, uuid UUID NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C983543D17F50A6 ON arc_photo.element (uuid)');
        $this->addSql('CREATE INDEX IDX_5C9835431F1F2A24 ON arc_photo.element (element_id)');
        $this->addSql('CREATE INDEX IDX_5C983543699B6BAF ON arc_photo.element (added_by)');
        $this->addSql('COMMENT ON COLUMN arc_photo.element.added_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C9835431F1F2A24 FOREIGN KEY (element_id) REFERENCES configuration_list.element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arc_photo.element ADD CONSTRAINT FK_5C983543699B6BAF FOREIGN KEY (added_by) REFERENCES arc_main.user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE arc_photo.element');
    }
}
