<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230821134612 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE organizations ADD company_id VARCHAR(255) NOT NULL, ADD operating_area VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
    }
}
