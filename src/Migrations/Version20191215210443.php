<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191215210443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE badge_glecteur_variable (id INT AUTO_INCREMENT NOT NULL, badge_id INT NOT NULL, glecteur_id INT NOT NULL, variable_id INT NOT NULL, INDEX IDX_BAC7C686F7A2C2FC (badge_id), INDEX IDX_BAC7C686B839C441 (glecteur_id), INDEX IDX_BAC7C686F3037E8E (variable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE badge_glecteur_variable ADD CONSTRAINT FK_BAC7C686F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('ALTER TABLE badge_glecteur_variable ADD CONSTRAINT FK_BAC7C686B839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id)');
        $this->addSql('ALTER TABLE badge_glecteur_variable ADD CONSTRAINT FK_BAC7C686F3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE badge_glecteur_variable');
    }
}
