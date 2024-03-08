<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230627112803 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE organizations SET location_lat = "0" WHERE location_lat = ""');
        $this->addSql('UPDATE organizations SET location_lng = "0" WHERE location_lng = ""');
        $this->addSql('ALTER TABLE organizations CHANGE location_lat _location_lat VARCHAR(255) NOT NULL, CHANGE location_lng _location_lng VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organizations ADD location_lat DOUBLE PRECISION NOT NULL, ADD location_lng DOUBLE PRECISION NOT NULL');
        $this->addSql('UPDATE organizations SET location_lat = CAST(_location_lat AS DECIMAL(10,6)), location_lng = CAST(_location_lng AS DECIMAL(10,6))');
        $this->addSql('ALTER TABLE organizations DROP COLUMN _location_lat, DROP COLUMN _location_lng');
    }

    public function down(Schema $schema) : void
    {
    }
}
