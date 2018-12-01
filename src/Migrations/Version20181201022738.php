<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201022738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE arc_photo.photo ADD coordinates Geography(Point) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN arc_photo.photo.coordinates IS \'(DC2Type:point_geog)\'');
        $this->addSql('ALTER TABLE arc_photo.element ADD sector Geometry(Polygon) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN arc_photo.element.sector IS \'(DC2Type:polygon_geom)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arc_photo.photo DROP coordinates');
        $this->addSql('ALTER TABLE arc_photo.element DROP sector');
    }
}
