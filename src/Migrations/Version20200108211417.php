<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108211417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE variable_glecteur (variable_id INT NOT NULL, glecteur_id INT NOT NULL, INDEX IDX_90618440F3037E8E (variable_id), INDEX IDX_90618440B839C441 (glecteur_id), PRIMARY KEY(variable_id, glecteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE variable_glecteur ADD CONSTRAINT FK_90618440F3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variable_glecteur ADD CONSTRAINT FK_90618440B839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE glecteur_variable');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE glecteur_variable (glecteur_id INT NOT NULL, variable_id INT NOT NULL, INDEX IDX_360FA570B839C441 (glecteur_id), INDEX IDX_360FA570F3037E8E (variable_id), PRIMARY KEY(glecteur_id, variable_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE glecteur_variable ADD CONSTRAINT FK_360FA570B839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE glecteur_variable ADD CONSTRAINT FK_360FA570F3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE variable_glecteur');
    }
}
