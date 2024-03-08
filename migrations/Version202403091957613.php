<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202403091957613 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('alter table organizations
    add constraint organizations_unique
        unique (email);
');

        $this->addSql("
        CREATE TRIGGER insert_user_after_organization
    AFTER INSERT ON organizations
    FOR EACH ROW
BEGIN
 INSERT INTO adminaut_user (organization_id, email, password, name, role, active, inserted, inserted_by, updated, updated_by, deleted, deleted_by, dtype)
    VALUES (NEW.id, NEW.email, '', NEW.title, 'organization', 1, NEW.inserted, 0, NEW.updated, 0, 0, 0, 0);
END;
        ");
    }

    public function down(Schema $schema) : void
    {
    }
}
