<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206144305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, vot_question_fr LONGTEXT NOT NULL, vot_question_en LONGTEXT DEFAULT NULL, vot_first_choice_fr VARCHAR(255) NOT NULL, vot_first_choice_en VARCHAR(255) DEFAULT NULL, vot_second_choice_fr VARCHAR(255) NOT NULL, vot_seconde_choice_en VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5A1085649D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085649D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vote');
    }
}
