<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206125034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, art_created_at DATETIME NOT NULL, art_title_fr VARCHAR(255) NOT NULL, art_title_en VARCHAR(255) DEFAULT NULL, art_content_fr LONGTEXT NOT NULL, art_content_en LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merchant (id INT AUTO_INCREMENT NOT NULL, mer_created_at DATETIME NOT NULL, mer_title_fr VARCHAR(255) NOT NULL, mer_title_en VARCHAR(255) DEFAULT NULL, mer_description_fr LONGTEXT NOT NULL, mer_description_en LONGTEXT DEFAULT NULL, mer_location_osm LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, new_created_at DATETIME NOT NULL, new_content_fr LONGTEXT NOT NULL, new_content_en LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_modification DATETIME NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, newsletters TINYINT(1) NOT NULL, votes TINYINT(1) NOT NULL, events TINYINT(1) NOT NULL, surveys TINYINT(1) NOT NULL, rgpd TINYINT(1) NOT NULL, user_terms_of_use TINYINT(1) NOT NULL, employee_terms_of_use TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE merchant');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE user');
    }
}
