<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260317082727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__proj_user AS SELECT id, login, roles, password, nom, prenom, date_naissance FROM proj_user');
        $this->addSql('DROP TABLE proj_user');
        $this->addSql('CREATE TABLE proj_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, pays_id INTEGER DEFAULT NULL, CONSTRAINT FK_3ADA00E9A6E44244 FOREIGN KEY (pays_id) REFERENCES proj_pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO proj_user (id, login, roles, password, nom, prenom, date_naissance) SELECT id, login, roles, password, nom, prenom, date_naissance FROM __temp__proj_user');
        $this->addSql('DROP TABLE __temp__proj_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON proj_user (login)');
        $this->addSql('CREATE INDEX IDX_3ADA00E9A6E44244 ON proj_user (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__proj_user AS SELECT id, login, roles, password, nom, prenom, date_naissance FROM proj_user');
        $this->addSql('DROP TABLE proj_user');
        $this->addSql('CREATE TABLE proj_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL)');
        $this->addSql('INSERT INTO proj_user (id, login, roles, password, nom, prenom, date_naissance) SELECT id, login, roles, password, nom, prenom, date_naissance FROM __temp__proj_user');
        $this->addSql('DROP TABLE __temp__proj_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON proj_user (login)');
    }
}
