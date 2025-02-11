<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130124829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, group_id_id INT DEFAULT NULL, status VARCHAR(100) NOT NULL, role VARCHAR(100) NOT NULL, INDEX IDX_8F02BF9D9D86650F (user_id_id), INDEX IDX_8F02BF9D2F68B530 (group_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D9D86650F');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D2F68B530');
        $this->addSql('DROP TABLE user_group');
    }
}
