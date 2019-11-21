<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191022162627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_33B3608D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__glecteur AS SELECT id, installation_id, nom, description, extention, app_id FROM glecteur');
        $this->addSql('DROP TABLE glecteur');
        $this->addSql('CREATE TABLE glecteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) NOT NULL COLLATE BINARY, extention INTEGER DEFAULT NULL, app_id INTEGER DEFAULT NULL, CONSTRAINT FK_33B3608D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO glecteur (id, installation_id, nom, description, extention, app_id) SELECT id, installation_id, nom, description, extention, app_id FROM __temp__glecteur');
        $this->addSql('DROP TABLE __temp__glecteur');
        $this->addSql('CREATE INDEX IDX_33B3608D167B88B4 ON glecteur (installation_id)');
        $this->addSql('DROP INDEX IDX_360FA570F3037E8E');
        $this->addSql('DROP INDEX IDX_360FA570B839C441');
        $this->addSql('CREATE TEMPORARY TABLE __temp__glecteur_variable AS SELECT glecteur_id, variable_id FROM glecteur_variable');
        $this->addSql('DROP TABLE glecteur_variable');
        $this->addSql('CREATE TABLE glecteur_variable (glecteur_id INTEGER NOT NULL, variable_id INTEGER NOT NULL, PRIMARY KEY(glecteur_id, variable_id), CONSTRAINT FK_360FA570B839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_360FA570F3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO glecteur_variable (glecteur_id, variable_id) SELECT glecteur_id, variable_id FROM __temp__glecteur_variable');
        $this->addSql('DROP TABLE __temp__glecteur_variable');
        $this->addSql('CREATE INDEX IDX_360FA570F3037E8E ON glecteur_variable (variable_id)');
        $this->addSql('CREATE INDEX IDX_360FA570B839C441 ON glecteur_variable (glecteur_id)');
        $this->addSql('DROP INDEX IDX_CC4D878D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__variable AS SELECT id, installation_id, nom, description, extension, translated_ph, app_id FROM variable');
        $this->addSql('DROP TABLE variable');
        $this->addSql('CREATE TABLE variable (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, extension VARCHAR(1000) DEFAULT NULL COLLATE BINARY, translated_ph VARCHAR(1000) DEFAULT NULL COLLATE BINARY, app_id INTEGER DEFAULT NULL, CONSTRAINT FK_CC4D878D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO variable (id, installation_id, nom, description, extension, translated_ph, app_id) SELECT id, installation_id, nom, description, extension, translated_ph, app_id FROM __temp__variable');
        $this->addSql('DROP TABLE __temp__variable');
        $this->addSql('CREATE INDEX IDX_CC4D878D167B88B4 ON variable (installation_id)');
        $this->addSql('DROP INDEX IDX_E6D6B297167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__profil AS SELECT id, installation_id, nom, description, extension, app_id FROM profil');
        $this->addSql('DROP TABLE profil');
        $this->addSql('CREATE TABLE profil (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, extension VARCHAR(255) DEFAULT NULL COLLATE BINARY, app_id INTEGER DEFAULT NULL, CONSTRAINT FK_E6D6B297167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO profil (id, installation_id, nom, description, extension, app_id) SELECT id, installation_id, nom, description, extension, app_id FROM __temp__profil');
        $this->addSql('DROP TABLE __temp__profil');
        $this->addSql('CREATE INDEX IDX_E6D6B297167B88B4 ON profil (installation_id)');
        $this->addSql('DROP INDEX IDX_FEF0481D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__badge AS SELECT id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id FROM badge');
        $this->addSql('DROP TABLE badge');
        $this->addSql('CREATE TABLE badge (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL COLLATE BINARY, nom VARCHAR(255) DEFAULT NULL COLLATE BINARY, operateur_createur INTEGER DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_deb_val DATETIME DEFAULT NULL, date_fin_val DATETIME DEFAULT NULL, date_deb_val2 DATETIME DEFAULT NULL, date_fin_val2 DATETIME DEFAULT NULL, valide BOOLEAN NOT NULL, code1 VARCHAR(64) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(255) DEFAULT NULL COLLATE BINARY, app_id INTEGER DEFAULT NULL, CONSTRAINT FK_FEF0481D167B88B4 FOREIGN KEY (installation_id) REFERENCES installation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO badge (id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id) SELECT id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id FROM __temp__badge');
        $this->addSql('DROP TABLE __temp__badge');
        $this->addSql('CREATE INDEX IDX_FEF0481D167B88B4 ON badge (installation_id)');
        $this->addSql('DROP INDEX IDX_D46A11D2F7A2C2FC');
        $this->addSql('DROP INDEX IDX_D46A11D2275ED078');
        $this->addSql('CREATE TEMPORARY TABLE __temp__badge_profil AS SELECT badge_id, profil_id FROM badge_profil');
        $this->addSql('DROP TABLE badge_profil');
        $this->addSql('CREATE TABLE badge_profil (badge_id INTEGER NOT NULL, profil_id INTEGER NOT NULL, PRIMARY KEY(badge_id, profil_id), CONSTRAINT FK_D46A11D2F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D46A11D2275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO badge_profil (badge_id, profil_id) SELECT badge_id, profil_id FROM __temp__badge_profil');
        $this->addSql('DROP TABLE __temp__badge_profil');
        $this->addSql('CREATE INDEX IDX_D46A11D2F7A2C2FC ON badge_profil (badge_id)');
        $this->addSql('CREATE INDEX IDX_D46A11D2275ED078 ON badge_profil (profil_id)');
        $this->addSql('DROP INDEX IDX_25787E1BF3037E8E');
        $this->addSql('DROP INDEX IDX_25787E1BB839C441');
        $this->addSql('DROP INDEX IDX_25787E1B275ED078');
        $this->addSql('CREATE TEMPORARY TABLE __temp__profil_glecteur_variable AS SELECT id, profil_id, glecteur_id, variable_id FROM profil_glecteur_variable');
        $this->addSql('DROP TABLE profil_glecteur_variable');
        $this->addSql('CREATE TABLE profil_glecteur_variable (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, profil_id INTEGER NOT NULL, glecteur_id INTEGER NOT NULL, variable_id INTEGER NOT NULL, CONSTRAINT FK_25787E1B275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_25787E1BB839C441 FOREIGN KEY (glecteur_id) REFERENCES glecteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_25787E1BF3037E8E FOREIGN KEY (variable_id) REFERENCES variable (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO profil_glecteur_variable (id, profil_id, glecteur_id, variable_id) SELECT id, profil_id, glecteur_id, variable_id FROM __temp__profil_glecteur_variable');
        $this->addSql('DROP TABLE __temp__profil_glecteur_variable');
        $this->addSql('CREATE INDEX IDX_25787E1BF3037E8E ON profil_glecteur_variable (variable_id)');
        $this->addSql('CREATE INDEX IDX_25787E1BB839C441 ON profil_glecteur_variable (glecteur_id)');
        $this->addSql('CREATE INDEX IDX_25787E1B275ED078 ON profil_glecteur_variable (profil_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_FEF0481D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__badge AS SELECT id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id FROM badge');
        $this->addSql('DROP TABLE badge');
        $this->addSql('CREATE TABLE badge (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, operateur_createur INTEGER DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_deb_val DATETIME DEFAULT NULL, date_fin_val DATETIME DEFAULT NULL, date_deb_val2 DATETIME DEFAULT NULL, date_fin_val2 DATETIME DEFAULT NULL, valide BOOLEAN NOT NULL, code1 VARCHAR(64) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, app_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO badge (id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id) SELECT id, installation_id, matricule, nom, operateur_createur, date_creation, date_deb_val, date_fin_val, date_deb_val2, date_fin_val2, valide, code1, prenom, app_id FROM __temp__badge');
        $this->addSql('DROP TABLE __temp__badge');
        $this->addSql('CREATE INDEX IDX_FEF0481D167B88B4 ON badge (installation_id)');
        $this->addSql('DROP INDEX IDX_D46A11D2F7A2C2FC');
        $this->addSql('DROP INDEX IDX_D46A11D2275ED078');
        $this->addSql('CREATE TEMPORARY TABLE __temp__badge_profil AS SELECT badge_id, profil_id FROM badge_profil');
        $this->addSql('DROP TABLE badge_profil');
        $this->addSql('CREATE TABLE badge_profil (badge_id INTEGER NOT NULL, profil_id INTEGER NOT NULL, PRIMARY KEY(badge_id, profil_id))');
        $this->addSql('INSERT INTO badge_profil (badge_id, profil_id) SELECT badge_id, profil_id FROM __temp__badge_profil');
        $this->addSql('DROP TABLE __temp__badge_profil');
        $this->addSql('CREATE INDEX IDX_D46A11D2F7A2C2FC ON badge_profil (badge_id)');
        $this->addSql('CREATE INDEX IDX_D46A11D2275ED078 ON badge_profil (profil_id)');
        $this->addSql('DROP INDEX IDX_33B3608D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__glecteur AS SELECT id, installation_id, nom, description, extention, app_id FROM glecteur');
        $this->addSql('DROP TABLE glecteur');
        $this->addSql('CREATE TABLE glecteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, extention INTEGER DEFAULT NULL, app_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO glecteur (id, installation_id, nom, description, extention, app_id) SELECT id, installation_id, nom, description, extention, app_id FROM __temp__glecteur');
        $this->addSql('DROP TABLE __temp__glecteur');
        $this->addSql('CREATE INDEX IDX_33B3608D167B88B4 ON glecteur (installation_id)');
        $this->addSql('DROP INDEX IDX_360FA570B839C441');
        $this->addSql('DROP INDEX IDX_360FA570F3037E8E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__glecteur_variable AS SELECT glecteur_id, variable_id FROM glecteur_variable');
        $this->addSql('DROP TABLE glecteur_variable');
        $this->addSql('CREATE TABLE glecteur_variable (glecteur_id INTEGER NOT NULL, variable_id INTEGER NOT NULL, PRIMARY KEY(glecteur_id, variable_id))');
        $this->addSql('INSERT INTO glecteur_variable (glecteur_id, variable_id) SELECT glecteur_id, variable_id FROM __temp__glecteur_variable');
        $this->addSql('DROP TABLE __temp__glecteur_variable');
        $this->addSql('CREATE INDEX IDX_360FA570B839C441 ON glecteur_variable (glecteur_id)');
        $this->addSql('CREATE INDEX IDX_360FA570F3037E8E ON glecteur_variable (variable_id)');
        $this->addSql('DROP INDEX IDX_E6D6B297167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__profil AS SELECT id, installation_id, nom, description, extension, app_id FROM profil');
        $this->addSql('DROP TABLE profil');
        $this->addSql('CREATE TABLE profil (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, app_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO profil (id, installation_id, nom, description, extension, app_id) SELECT id, installation_id, nom, description, extension, app_id FROM __temp__profil');
        $this->addSql('DROP TABLE __temp__profil');
        $this->addSql('CREATE INDEX IDX_E6D6B297167B88B4 ON profil (installation_id)');
        $this->addSql('DROP INDEX IDX_25787E1B275ED078');
        $this->addSql('DROP INDEX IDX_25787E1BB839C441');
        $this->addSql('DROP INDEX IDX_25787E1BF3037E8E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__profil_glecteur_variable AS SELECT id, profil_id, glecteur_id, variable_id FROM profil_glecteur_variable');
        $this->addSql('DROP TABLE profil_glecteur_variable');
        $this->addSql('CREATE TABLE profil_glecteur_variable (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, profil_id INTEGER NOT NULL, glecteur_id INTEGER NOT NULL, variable_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO profil_glecteur_variable (id, profil_id, glecteur_id, variable_id) SELECT id, profil_id, glecteur_id, variable_id FROM __temp__profil_glecteur_variable');
        $this->addSql('DROP TABLE __temp__profil_glecteur_variable');
        $this->addSql('CREATE INDEX IDX_25787E1B275ED078 ON profil_glecteur_variable (profil_id)');
        $this->addSql('CREATE INDEX IDX_25787E1BB839C441 ON profil_glecteur_variable (glecteur_id)');
        $this->addSql('CREATE INDEX IDX_25787E1BF3037E8E ON profil_glecteur_variable (variable_id)');
        $this->addSql('DROP INDEX IDX_CC4D878D167B88B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__variable AS SELECT id, installation_id, nom, description, extension, translated_ph, app_id FROM variable');
        $this->addSql('DROP TABLE variable');
        $this->addSql('CREATE TABLE variable (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, installation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, extension VARCHAR(1000) DEFAULT NULL, translated_ph VARCHAR(1000) DEFAULT NULL, app_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO variable (id, installation_id, nom, description, extension, translated_ph, app_id) SELECT id, installation_id, nom, description, extension, translated_ph, app_id FROM __temp__variable');
        $this->addSql('DROP TABLE __temp__variable');
        $this->addSql('CREATE INDEX IDX_CC4D878D167B88B4 ON variable (installation_id)');
    }
}
