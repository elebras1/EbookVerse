<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622075816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(60) NOT NULL, description VARCHAR(1000) DEFAULT NULL, etat VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (pseudo VARCHAR(60) NOT NULL, mot_de_passe VARCHAR(60) NOT NULL, PRIMARY KEY(pseudo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, description VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, compte_id VARCHAR(60) NOT NULL, message VARCHAR(300) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', etat VARCHAR(1) NOT NULL, INDEX IDX_C0B9F90FF2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecrit (livre_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_A418E3837D925CB (livre_id), INDEX IDX_A418E3860BB6FE6 (auteur_id), PRIMARY KEY(livre_id, auteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(40) NOT NULL, etat VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, pseudo_id VARCHAR(60) NOT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(1000) NOT NULL, annee INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', image VARCHAR(150) NOT NULL, ebook VARCHAR(150) NOT NULL, etat VARCHAR(1) NOT NULL, INDEX IDX_AC634F9920E394C2 (pseudo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (cpt_pseudo VARCHAR(60) NOT NULL, prenom VARCHAR(60) DEFAULT NULL, nom VARCHAR(60) DEFAULT NULL, validite VARCHAR(1) NOT NULL, roles JSON NOT NULL, date_creation DATE NOT NULL, PRIMARY KEY(cpt_pseudo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (livre_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_8CDE572937D925CB (livre_id), INDEX IDX_8CDE57294296D31F (genre_id), PRIMARY KEY(livre_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (pseudo)');
        $this->addSql('ALTER TABLE ecrit ADD CONSTRAINT FK_A418E3837D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE ecrit ADD CONSTRAINT FK_A418E3860BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F9920E394C2 FOREIGN KEY (pseudo_id) REFERENCES compte (pseudo)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297E370FADA FOREIGN KEY (cpt_pseudo) REFERENCES compte (pseudo)');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE572937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE57294296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FF2C56620');
        $this->addSql('ALTER TABLE ecrit DROP FOREIGN KEY FK_A418E3837D925CB');
        $this->addSql('ALTER TABLE ecrit DROP FOREIGN KEY FK_A418E3860BB6FE6');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F9920E394C2');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297E370FADA');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE572937D925CB');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE57294296D31F');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE ecrit');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
