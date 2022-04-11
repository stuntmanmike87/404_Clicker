<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331203131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, max_points DOUBLE PRECISION NOT NULL, path_img VARCHAR(255) NOT NULL, nom_level VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD level_id INT DEFAULT NULL, ADD token VARCHAR(255) DEFAULT NULL, ADD token_date_valid DATETIME DEFAULT NULL, DROP is_verified');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495FB14BA7 ON user (level_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495FB14BA7');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP INDEX IDX_8D93D6495FB14BA7 ON user');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, DROP level_id, DROP token, DROP token_date_valid');
    }
}
