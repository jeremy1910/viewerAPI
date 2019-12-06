<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130121400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE glecteur (id INT AUTO_INCREMENT NOT NULL, installation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, extention INT DEFAULT NULL, app_id INT DEFAULT NULL, INDEX IDX_33B3608D167B88B4 (installation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE glecteur_variable (glecteur_id INT NOT NULL, variable_id INT NOT NULL, INDEX IDX_360FA570B839C441 (glecteur_id), INDEX IDX_360FA570F3037E8E (variable_id), PRIMARY KEY(glecteur_id, variable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variable (id INT AUTO_INCREMENT NOT NULL, installation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, extension VARCHAR(1000) DEFAULT NULL, translated_ph VARCHAR(1000) DEFAULT NULL, app_id INT DEFAULT NULL, INDEX IDX_CC4D878D167B88B4 (installation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, installation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, app_id INT DEFAULT NULL, INDEX IDX_E6D6B297167B88B4 (installation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, installation_id INT DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, operateur_createur INT DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_deb_val DATETIME DEFAULT NULL, date_fin_val DATETIME DEFAULT NULL, date_deb_val2 DATETIME DEFAULT NULL, date_fin_val2 DATETIME DEFAULT NULL, valide TINYINT(1) NOT NULL, code1 VARCHAR(64) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, app_id INT DEFAULT NULL, INDEX IDX_FEF0481D167B88B4 (installation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge_profil (badge_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_D46A11D2F7A2C2FC (badge_id), INDEX IDX_D46A11D2275ED078 (profil_id), PRIMARY KEY(badge_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_glecteur_variable (id INT AUTO_INCREMENT NOT NULL, profil_id INT NOT NULL, glecteur_id INT NOT NULL, variable_id INT NOT NULL, INDEX IDX_25787E1B275ED078 (profil_id), INDEX IDX_25787E1BB839C441 (glecteur_id), INDEX IDX_25787E1BF3037E8E (variable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE glecteur ADD CONSTRAINT FK_33B3608D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id)');
        $this->addSql('ALTER TABLE glecteur_variable ADD CONSTRAINT FK_360FA570B839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE glecteur_variable ADD CONSTRAINT FK_360FA570F3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variable ADD CONSTRAINT FK_CC4D878D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id)');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id)');
        $this->addSql('ALTER TABLE badge_profil ADD CONSTRAINT FK_D46A11D2F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE badge_profil ADD CONSTRAINT FK_D46A11D2275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_glecteur_variable ADD CONSTRAINT FK_25787E1B275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE profil_glecteur_variable ADD CONSTRAINT FK_25787E1BB839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id)');
        $this->addSql('ALTER TABLE profil_glecteur_variable ADD CONSTRAINT FK_25787E1BF3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE glecteur_variable DROP FOREIGN KEY FK_360FA570B839C441');
        $this->addSql('ALTER TABLE profil_glecteur_variable DROP FOREIGN KEY FK_25787E1BB839C441');
        $this->addSql('ALTER TABLE glecteur_variable DROP FOREIGN KEY FK_360FA570F3037E8E');
        $this->addSql('ALTER TABLE profil_glecteur_variable DROP FOREIGN KEY FK_25787E1BF3037E8E');
        $this->addSql('ALTER TABLE badge_profil DROP FOREIGN KEY FK_D46A11D2275ED078');
        $this->addSql('ALTER TABLE profil_glecteur_variable DROP FOREIGN KEY FK_25787E1B275ED078');
        $this->addSql('ALTER TABLE glecteur DROP FOREIGN KEY FK_33B3608D167B88B4');
        $this->addSql('ALTER TABLE variable DROP FOREIGN KEY FK_CC4D878D167B88B4');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297167B88B4');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D167B88B4');
        $this->addSql('ALTER TABLE badge_profil DROP FOREIGN KEY FK_D46A11D2F7A2C2FC');
        $this->addSql('DROP TABLE glecteur');
        $this->addSql('DROP TABLE glecteur_variable');
        $this->addSql('DROP TABLE variable');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE installation');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE badge_profil');
        $this->addSql('DROP TABLE profil_glecteur_variable');
    }
}
