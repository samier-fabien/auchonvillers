<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206141048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('ALTER TABLE newsletter ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7E8585C8A76ED395 ON newsletter (user_id)');
        $this->addSql('ALTER TABLE user ADD newsletter TINYINT(1) NOT NULL, ADD vote TINYINT(1) NOT NULL, ADD event TINYINT(1) NOT NULL, ADD survey TINYINT(1) NOT NULL, DROP newsletters, DROP votes, DROP events, DROP surveys');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('ALTER TABLE article DROP user_id');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8A76ED395');
        $this->addSql('DROP INDEX IDX_7E8585C8A76ED395 ON newsletter');
        $this->addSql('ALTER TABLE newsletter DROP user_id');
        $this->addSql('ALTER TABLE user ADD newsletters TINYINT(1) NOT NULL, ADD votes TINYINT(1) NOT NULL, ADD events TINYINT(1) NOT NULL, ADD surveys TINYINT(1) NOT NULL, DROP newsletter, DROP vote, DROP event, DROP survey');
    }
}
