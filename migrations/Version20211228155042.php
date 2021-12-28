<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228155042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ballots (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vote_id INT NOT NULL, bal_vote TINYINT(1) NOT NULL, INDEX IDX_AE0B9CD3A76ED395 (user_id), INDEX IDX_AE0B9CD372DCDAFC (vote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, survey_id INT NOT NULL, opi_opinion LONGTEXT NOT NULL, INDEX IDX_BEAF78D0A76ED395 (user_id), INDEX IDX_BEAF78D0B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE surveys (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sur_created_at DATETIME NOT NULL, sur_begining DATETIME NOT NULL, sur_end DATETIME NOT NULL, sur_content_fr LONGTEXT NOT NULL, sur_content_en LONGTEXT DEFAULT NULL, sur_question_fr VARCHAR(255) NOT NULL, sur_question_en VARCHAR(255) DEFAULT NULL, INDEX IDX_AFA82EA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vot_created_at DATETIME NOT NULL, vot_begining DATETIME NOT NULL, vot_end DATETIME NOT NULL, vot_content_fr LONGTEXT NOT NULL, vot_content_en LONGTEXT DEFAULT NULL, vot_question_fr VARCHAR(255) NOT NULL, vot_question_en VARCHAR(255) DEFAULT NULL, vot_first_choice_fr VARCHAR(255) NOT NULL, vot_first_choice_en VARCHAR(255) DEFAULT NULL, vot_second_choice_fr VARCHAR(255) NOT NULL, vot_second_choice_en VARCHAR(255) DEFAULT NULL, INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ballots ADD CONSTRAINT FK_AE0B9CD3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ballots ADD CONSTRAINT FK_AE0B9CD372DCDAFC FOREIGN KEY (vote_id) REFERENCES votes (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0B3FE509D FOREIGN KEY (survey_id) REFERENCES surveys (id)');
        $this->addSql('ALTER TABLE surveys ADD CONSTRAINT FK_AFA82EA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attends ADD CONSTRAINT FK_AA60FEC9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attends ADD CONSTRAINT FK_AA60FEC971F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D0B3FE509D');
        $this->addSql('ALTER TABLE ballots DROP FOREIGN KEY FK_AE0B9CD372DCDAFC');
        $this->addSql('DROP TABLE ballots');
        $this->addSql('DROP TABLE opinions');
        $this->addSql('DROP TABLE surveys');
        $this->addSql('DROP TABLE votes');
        $this->addSql('ALTER TABLE attends DROP FOREIGN KEY FK_AA60FEC9A76ED395');
        $this->addSql('ALTER TABLE attends DROP FOREIGN KEY FK_AA60FEC971F7E88B');
    }
}
