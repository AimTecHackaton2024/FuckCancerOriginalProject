<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230316160049 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE organizations (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, photo_main INT DEFAULT NULL, photo_1 INT DEFAULT NULL, photo_2 INT DEFAULT NULL, photo_3 INT DEFAULT NULL, photo_4 INT DEFAULT NULL, title VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_published DATE DEFAULT NULL, show_from DATETIME DEFAULT NULL, show_to DATETIME DEFAULT NULL, show_on_homepage TINYINT(1) NOT NULL, perex LONGTEXT NOT NULL, article LONGTEXT NOT NULL, homepage VARCHAR(255) NOT NULL, facebook VARCHAR(255) NOT NULL, instagram VARCHAR(255) NOT NULL, twitter VARCHAR(255) NOT NULL, linkedin VARCHAR(255) NOT NULL, tiktok VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, location_lat VARCHAR(255) NOT NULL, location_lng VARCHAR(255) NOT NULL, official_name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, validation_status LONGTEXT NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, INDEX IDX_427C1C7F12469DE2 (category_id), UNIQUE INDEX UNIQ_427C1C7F3E197F90 (photo_main), UNIQUE INDEX UNIQ_427C1C7F45EA9889 (photo_1), UNIQUE INDEX UNIQ_427C1C7FDCE3C933 (photo_2), UNIQUE INDEX UNIQ_427C1C7FABE4F9A5 (photo_3), UNIQUE INDEX UNIQ_427C1C7F35806C06 (photo_4), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organizations_tags (organization_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_CC70C7B732C8A3DE (organization_id), INDEX IDX_CC70C7B7BAD26311 (tag_id), PRIMARY KEY(organization_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F12469DE2 FOREIGN KEY (category_id) REFERENCES organization_categories (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F3E197F90 FOREIGN KEY (photo_main) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F45EA9889 FOREIGN KEY (photo_1) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7FDCE3C933 FOREIGN KEY (photo_2) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7FABE4F9A5 FOREIGN KEY (photo_3) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F35806C06 FOREIGN KEY (photo_4) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE organizations_tags ADD CONSTRAINT FK_CC70C7B732C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id)');
        $this->addSql('ALTER TABLE organizations_tags ADD CONSTRAINT FK_CC70C7B7BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
    }

    public function down(Schema $schema) : void
    {
    }
}
