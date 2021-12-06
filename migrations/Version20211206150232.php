<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206150232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ballot (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, vote_id INT NOT NULL, bal_vote TINYINT(1) NOT NULL, INDEX IDX_D59CE9BDA76ED395 (user_id), INDEX IDX_D59CE9BD72DCDAFC (vote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ballot ADD CONSTRAINT FK_D59CE9BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ballot ADD CONSTRAINT FK_D59CE9BD72DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ballot');
    }
}
