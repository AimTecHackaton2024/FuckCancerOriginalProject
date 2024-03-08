<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230317162257 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE blog_posts (id INT AUTO_INCREMENT NOT NULL, photo_main INT DEFAULT NULL, photo_1 INT DEFAULT NULL, photo_2 INT DEFAULT NULL, photo_3 INT DEFAULT NULL, photo_4 INT DEFAULT NULL, title VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_published DATE DEFAULT NULL, perex LONGTEXT NOT NULL, article LONGTEXT NOT NULL, youtube_video VARCHAR(255) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_78B2F9323E197F90 (photo_main), UNIQUE INDEX UNIQ_78B2F93245EA9889 (photo_1), UNIQUE INDEX UNIQ_78B2F932DCE3C933 (photo_2), UNIQUE INDEX UNIQ_78B2F932ABE4F9A5 (photo_3), UNIQUE INDEX UNIQ_78B2F93235806C06 (photo_4), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F9323E197F90 FOREIGN KEY (photo_main) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F93245EA9889 FOREIGN KEY (photo_1) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932DCE3C933 FOREIGN KEY (photo_2) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932ABE4F9A5 FOREIGN KEY (photo_3) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F93235806C06 FOREIGN KEY (photo_4) REFERENCES adminaut_file_manager (id)');
    }

    public function down(Schema $schema) : void
    {
    }
}
