<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216211721 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE badge_glecteur_variable ADD installation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE badge_glecteur_variable ADD CONSTRAINT FK_BAC7C686167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id)');
        $this->addSql('CREATE INDEX IDX_BAC7C686167B88B4 ON badge_glecteur_variable (installation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE badge_glecteur_variable DROP FOREIGN KEY FK_BAC7C686167B88B4');
        $this->addSql('DROP INDEX IDX_BAC7C686167B88B4 ON badge_glecteur_variable');
        $this->addSql('ALTER TABLE badge_glecteur_variable DROP installation_id');
    }
}
