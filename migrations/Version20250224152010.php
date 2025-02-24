<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224152010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, password VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, create_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, leader_id INT DEFAULT NULL, member_id INT DEFAULT NULL, room_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content VARCHAR(1000) NOT NULL, path_attachment VARCHAR(255) DEFAULT NULL, result_content VARCHAR(255) DEFAULT NULL, result_attachment VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, review_date DATETIME DEFAULT NULL, finish_date DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_527EDB2573154ED4 (leader_id), INDEX IDX_527EDB257597D3FE (member_id), INDEX IDX_527EDB2554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_room (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, room_id INT DEFAULT NULL, status VARCHAR(100) NOT NULL, role VARCHAR(100) NOT NULL, join_date DATETIME DEFAULT NULL, INDEX IDX_81E1D52A76ED395 (user_id), INDEX IDX_81E1D5254177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2573154ED4 FOREIGN KEY (leader_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257597D3FE FOREIGN KEY (member_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2554177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D52A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_room ADD CONSTRAINT FK_81E1D5254177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D2F68B530');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D9D86650F');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(180) NOT NULL, ADD lastname VARCHAR(180) NOT NULL, DROP username, DROP fullname, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, create_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, group_id_id INT DEFAULT NULL, status VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8F02BF9D9D86650F (user_id_id), INDEX IDX_8F02BF9D2F68B530 (group_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2573154ED4');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB257597D3FE');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2554177093');
        $this->addSql('ALTER TABLE user_room DROP FOREIGN KEY FK_81E1D52A76ED395');
        $this->addSql('ALTER TABLE user_room DROP FOREIGN KEY FK_81E1D5254177093');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user_room');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) DEFAULT NULL, ADD fullname VARCHAR(255) NOT NULL, DROP firstname, DROP lastname, CHANGE email email VARCHAR(255) NOT NULL');
    }
}
