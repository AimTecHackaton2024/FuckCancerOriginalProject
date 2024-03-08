<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230316130448 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE adminaut_file_manager (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, mimetype VARCHAR(255) NOT NULL, savepath VARCHAR(255) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adminaut_file_manager_keyword (id INT AUTO_INCREMENT NOT NULL, fileid INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_649D8AEE3A416C07 (fileid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adminaut_user_access_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, hash VARCHAR(255) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_102FF6BDD1B862B8 (hash), INDEX IDX_102FF6BDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adminaut_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, password_change_on_next_logon TINYINT(1) DEFAULT \'0\' NOT NULL, role VARCHAR(128) NOT NULL, language VARCHAR(128) DEFAULT \'en\' NOT NULL, status INT DEFAULT 2 NOT NULL, password_recovery_key VARCHAR(255) DEFAULT NULL, password_recovery_expires_at DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F160FDACE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adminaut_user_login (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type INT DEFAULT 0 NOT NULL, ip_address VARCHAR(255) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, inserted DATETIME NOT NULL, inserted_by INT NOT NULL, updated DATETIME NOT NULL, updated_by INT NOT NULL, deleted TINYINT(1) NOT NULL, deleted_by INT NOT NULL, deleted_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', active TINYINT(1) NOT NULL, INDEX IDX_716A8C11A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adminaut_file_manager_keyword ADD CONSTRAINT FK_649D8AEE3A416C07 FOREIGN KEY (fileid) REFERENCES adminaut_file_manager (id)');
        $this->addSql('ALTER TABLE adminaut_user_access_token ADD CONSTRAINT FK_102FF6BDA76ED395 FOREIGN KEY (user_id) REFERENCES adminaut_user (id)');
        $this->addSql('ALTER TABLE adminaut_user_login ADD CONSTRAINT FK_716A8C11A76ED395 FOREIGN KEY (user_id) REFERENCES adminaut_user (id)');
        $this->addSql("INSERT INTO `adminaut_user` (`id`, `name`, `email`, `password`, `password_change_on_next_logon`, `role`, `language`, `status`, `password_recovery_key`, `password_recovery_expires_at`, `active`, `inserted`, `inserted_by`, `updated`, `updated_by`, `deleted`, `deleted_by`, `deleted_data`, `dtype`) VALUES (1, 'Adminaut', 'adminaut@mfcc.cz', '$2y$10$0F2ZVjI9mm039IVH2xLA7OKBeInQjkXNUYPuMVSP1LVBOeTjp1tOS', 0, 'admin', 'en', 2, NULL, NULL, 1, '2023-03-16 14:07:48', 0, '2023-03-16 14:07:48', 0, 0, 0, NULL, 'userentity');");
    }

    public function down(Schema $schema) : void
    {
    }
}
