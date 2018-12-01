<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201110143 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE arc_photo.element DROP a_x');
        $this->addSql('ALTER TABLE arc_photo.element DROP a_y');
        $this->addSql('ALTER TABLE arc_photo.element DROP b_x');
        $this->addSql('ALTER TABLE arc_photo.element DROP b_y');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arc_photo.element ADD a_x DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE arc_photo.element ADD a_y DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE arc_photo.element ADD b_x DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE arc_photo.element ADD b_y DOUBLE PRECISION NOT NULL');
    }
}
