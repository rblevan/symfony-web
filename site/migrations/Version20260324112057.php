<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260324112057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proj_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantite INTEGER NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, CONSTRAINT FK_E57B71B3A76ED395 FOREIGN KEY (user_id) REFERENCES proj_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E57B71B34584665A FOREIGN KEY (product_id) REFERENCES proj_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E57B71B3A76ED395 ON proj_panier (user_id)');
        $this->addSql('CREATE INDEX IDX_E57B71B34584665A ON proj_panier (product_id)');
        $this->addSql('CREATE TABLE proj_pays (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(2) NOT NULL)');
        $this->addSql('CREATE TABLE proj_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, quantite_stock INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE proj_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, pays_id INTEGER DEFAULT NULL, CONSTRAINT FK_3ADA00E9A6E44244 FOREIGN KEY (pays_id) REFERENCES proj_pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3ADA00E9A6E44244 ON proj_user (pays_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON proj_user (login)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_NOM_PRENOM ON proj_user (nom, prenom)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE proj_panier');
        $this->addSql('DROP TABLE proj_pays');
        $this->addSql('DROP TABLE proj_product');
        $this->addSql('DROP TABLE proj_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
