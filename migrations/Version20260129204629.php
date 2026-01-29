<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260129204629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE albergo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL, obsoleto BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE porteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL, pin VARCHAR(255) NOT NULL, obsoleto BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE prenotazione (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cliente VARCHAR(255) NOT NULL, dal DATE NOT NULL, al DATE NOT NULL, pax_adulti INTEGER NOT NULL, pax_bambini INTEGER NOT NULL, pax_adolescenti INTEGER NOT NULL, costo NUMERIC(14, 2) NOT NULL, note CLOB DEFAULT NULL, fk_porteur_id INTEGER NOT NULL, fk_tipologia_ospitalita_id INTEGER NOT NULL, fk_albergo_id INTEGER NOT NULL, fk_tipologia_sistemazione_id INTEGER NOT NULL, fk_tariffa_id INTEGER NOT NULL, CONSTRAINT FK_F89BBC7FA1188EBE FOREIGN KEY (fk_porteur_id) REFERENCES porteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F89BBC7F28DDFF56 FOREIGN KEY (fk_tipologia_ospitalita_id) REFERENCES tipologia_ospitalita (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F89BBC7F4B6B877F FOREIGN KEY (fk_albergo_id) REFERENCES albergo (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F89BBC7F502098C9 FOREIGN KEY (fk_tipologia_sistemazione_id) REFERENCES tipologia_sistemazione (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F89BBC7F85406FD5 FOREIGN KEY (fk_tariffa_id) REFERENCES tariffa (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F89BBC7FA1188EBE ON prenotazione (fk_porteur_id)');
        $this->addSql('CREATE INDEX IDX_F89BBC7F28DDFF56 ON prenotazione (fk_tipologia_ospitalita_id)');
        $this->addSql('CREATE INDEX IDX_F89BBC7F4B6B877F ON prenotazione (fk_albergo_id)');
        $this->addSql('CREATE INDEX IDX_F89BBC7F502098C9 ON prenotazione (fk_tipologia_sistemazione_id)');
        $this->addSql('CREATE INDEX IDX_F89BBC7F85406FD5 ON prenotazione (fk_tariffa_id)');
        $this->addSql('CREATE TABLE tariffa (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL, obsoleto BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE tipologia_ospitalita (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL, obsoleto BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE tipologia_sistemazione (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descrizione VARCHAR(255) NOT NULL, obsoleto BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE albergo');
        $this->addSql('DROP TABLE porteur');
        $this->addSql('DROP TABLE prenotazione');
        $this->addSql('DROP TABLE tariffa');
        $this->addSql('DROP TABLE tipologia_ospitalita');
        $this->addSql('DROP TABLE tipologia_sistemazione');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
