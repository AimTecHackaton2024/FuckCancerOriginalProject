<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230628112541 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE organization_categories ADD icon VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
    }
}
