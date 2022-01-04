<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220104153839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT NOT NULL, art_created_at DATETIME NOT NULL, art_content_fr LONGTEXT NOT NULL, art_content_en LONGTEXT DEFAULT NULL, art_order_of_appearance INT DEFAULT NULL, art_title_fr VARCHAR(255) NOT NULL, art_title_en VARCHAR(255) DEFAULT NULL, INDEX IDX_23A0E66A76ED395 (user_id), INDEX IDX_23A0E6612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attends (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_AA60FEC9A76ED395 (user_id), INDEX IDX_AA60FEC971F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ballots (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vote_id INT NOT NULL, bal_vote TINYINT(1) NOT NULL, INDEX IDX_AE0B9CD3A76ED395 (user_id), INDEX IDX_AE0B9CD372DCDAFC (vote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, cat_name_fr VARCHAR(255) NOT NULL, cat_name_en VARCHAR(255) DEFAULT NULL, cat_description_fr LONGTEXT NOT NULL, cat_description_en LONGTEXT DEFAULT NULL, cat_order_of_appearance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cha_created_at DATETIME NOT NULL, cha_resolved TINYINT(1) NOT NULL, cha_last_message DATETIME NOT NULL, INDEX IDX_659DF2AAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, eve_created_at DATETIME NOT NULL, eve_begining DATETIME NOT NULL, eve_end DATETIME NOT NULL, eve_content_fr LONGTEXT NOT NULL, eve_content_en LONGTEXT DEFAULT NULL, eve_location_osm VARCHAR(255) DEFAULT NULL, INDEX IDX_5387574AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, chat_id INT NOT NULL, mes_created_at DATETIME NOT NULL, mes_content LONGTEXT NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), INDEX IDX_B6BD307F1A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, new_created_at DATETIME NOT NULL, new_content_fr LONGTEXT NOT NULL, new_content_en LONGTEXT DEFAULT NULL, INDEX IDX_7E8585C8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, survey_id INT NOT NULL, opi_opinion LONGTEXT NOT NULL, INDEX IDX_BEAF78D0A76ED395 (user_id), INDEX IDX_BEAF78D0B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE surveys (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sur_created_at DATETIME NOT NULL, sur_begining DATETIME NOT NULL, sur_end DATETIME NOT NULL, sur_content_fr LONGTEXT NOT NULL, sur_content_en LONGTEXT DEFAULT NULL, sur_question_fr VARCHAR(255) NOT NULL, sur_question_en VARCHAR(255) DEFAULT NULL, INDEX IDX_AFA82EA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_modification DATETIME NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, newsletter TINYINT(1) NOT NULL, vote TINYINT(1) NOT NULL, event TINYINT(1) NOT NULL, survey TINYINT(1) NOT NULL, rgpd TINYINT(1) NOT NULL, user_terms_of_use TINYINT(1) NOT NULL, employee_terms_of_use TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vot_created_at DATETIME NOT NULL, vot_begining DATETIME NOT NULL, vot_end DATETIME NOT NULL, vot_content_fr LONGTEXT NOT NULL, vot_content_en LONGTEXT DEFAULT NULL, vot_question_fr VARCHAR(255) NOT NULL, vot_question_en VARCHAR(255) DEFAULT NULL, vot_first_choice_fr VARCHAR(255) NOT NULL, vot_first_choice_en VARCHAR(255) DEFAULT NULL, vot_second_choice_fr VARCHAR(255) NOT NULL, vot_second_choice_en VARCHAR(255) DEFAULT NULL, INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE attends ADD CONSTRAINT FK_AA60FEC9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attends ADD CONSTRAINT FK_AA60FEC971F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE ballots ADD CONSTRAINT FK_AE0B9CD3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ballots ADD CONSTRAINT FK_AE0B9CD372DCDAFC FOREIGN KEY (vote_id) REFERENCES votes (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0B3FE509D FOREIGN KEY (survey_id) REFERENCES surveys (id)');
        $this->addSql('ALTER TABLE surveys ADD CONSTRAINT FK_AFA82EA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE attends DROP FOREIGN KEY FK_AA60FEC971F7E88B');
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D0B3FE509D');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE attends DROP FOREIGN KEY FK_AA60FEC9A76ED395');
        $this->addSql('ALTER TABLE ballots DROP FOREIGN KEY FK_AE0B9CD3A76ED395');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA76ED395');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8A76ED395');
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D0A76ED395');
        $this->addSql('ALTER TABLE surveys DROP FOREIGN KEY FK_AFA82EA7A76ED395');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395');
        $this->addSql('ALTER TABLE ballots DROP FOREIGN KEY FK_AE0B9CD372DCDAFC');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE attends');
        $this->addSql('DROP TABLE ballots');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE opinions');
        $this->addSql('DROP TABLE surveys');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE votes');
    }
}
