<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230501210427 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE organizations ADD more_venues TINYINT(1) NOT NULL, ADD notes LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
    }
}
