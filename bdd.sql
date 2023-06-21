CREATE TABLE t_compte_cpt 
(
    cpt_pseudo VARCHAR(60) NOT NULL,
    cpt_mot_de_passe VARCHAR(15) NOT NULL,
    PRIMARY KEY (cpt_pseudo)
);

CREATE TABLE t_profil_pfl
(
    pfl_prenom VARCHAR(60),
    pfl_nom VARCHAR(60),
    pfl_validite CHAR(1) NOT NULL,
    pfl_role CHAR(1) NOT NULL,
    pfl_date_creation DATE NOT NULL,
    cpt_pseudo VARCHAR(60) NOT NULL,
    PRIMARY KEY (cpt_pseudo),
    CONSTRAINT FK_profil_compte FOREIGN KEY (cpt_pseudo)
    REFERENCES t_compte_cpt (cpt_pseudo)
);

CREATE TABLE t_discussion_dis 
(
    dis_id INT NOT NULL AUTO_INCREMENT,
    dis_message VARCHAR(300) NOT NULL,
    dis_date DATE NOT NULL,
    dis_etat CHAR(1) NOT NULL,
    cpt_pseudo VARCHAR(60) NOT NULL,
    PRIMARY KEY (dis_id),
    CONSTRAINT FK_discussion_compte FOREIGN KEY (cpt_pseudo)
    REFERENCES t_compte_cpt (cpt_pseudo)
);

CREATE TABLE t_configuration_conf 
(
    conf_id INT NOT NULL AUTO_INCREMENT,
    conf_nom VARCHAR(60) NOT NULL,
    conf_description VARCHAR(1000) NOT NULL,
    PRIMARY KEY (conf_id)
);

CREATE TABLE t_livre_lvr
(
    lvr_id INT NOT NULL AUTO_INCREMENT,
    lvr_titre VARCHAR(100) NOT NULL,
    lvr_description VARCHAR(1000) NOT NULL,
    lvr_annee INT(4) NOT NULL,
    lvr_date DATE NOT NULL,
    lvr_image VARCHAR(150) NOT NULL,
    cpt_pseudo VARCHAR(60) NOT NULL,
    PRIMARY KEY(lvr_id),
    CONSTRAINT FK_livre_compte FOREIGN KEY (cpt_pseudo)
    REFERENCES t_compte_cpt (cpt_pseudo)
);

CREATE TABLE t_auteur_aut
(
    aut_id INT NOT NULL AUTO_INCREMENT,
    aut_nom VARCHAR(60) NOT NULL,
    aut_prenom VARCHAR(60) NOT NULL,
    aut_description VARCHAR(1000),
    aut_etat CHAR(1) NOT NULL,
    PRIMARY KEY (aut_id)
);

CREATE TABLE t_ecrit_ecr 
(
    lvr_id INT NOT NULL,
    aut_id INT NOT NULL,
    PRIMARY KEY(lvr_id,aut_id),
    CONSTRAINT FK_ecrit_livre FOREIGN KEY (lvr_id)
    REFERENCES t_livre_lvr (lvr_id),
    CONSTRAINT FK_ecrit_auteur FOREIGN KEY (aut_id)
    REFERENCES t_auteur_aut (aut_id)
);

CREATE TABLE t_genre_gnr
(
    gnr_id INT NOT NULL AUTO_INCREMENT,
    gnr_nom VARCHAR(40) NOT NULL,
    gnr_etat CHAR(1) NOT NULL,
    PRIMARY KEY (gnr_id)
);

CREATE TABLE t_type_typ 
(
    lvr_id INT NOT NULL,
    gnr_id INT NOT NULL,
    PRIMARY KEY (lvr_id, gnr_id),
    CONSTRAINT FK_type_livre FOREIGN KEY(lvr_id)
    REFERENCES t_livre_lvr (lvr_id),
    CONSTRAINT FK_type_genre FOREIGN KEY(gnr_id)
    REFERENCES t_genre_gnr (gnr_id)
);


INSERT INTO t_compte_cpt (cpt_pseudo, cpt_mot_de_passe) VALUES
('utilisateur1', 'motdepasse1'),
('utilisateur2', 'motdepasse2'),
('utilisateur3', 'motdepasse3');

INSERT INTO t_profil_pfl (pfl_prenom, pfl_nom, pfl_validite, pfl_role, pfl_date_creation, cpt_pseudo) VALUES
('John', 'Doe', 'V', 'A', '2023-05-19', 'utilisateur1'),
('Jane', 'Smith', 'V', 'U', '2023-05-19', 'utilisateur2'),
('Alice', 'Johnson', 'I', 'U', '2023-05-19', 'utilisateur3');

INSERT INTO t_discussion_dis (dis_message, dis_date, dis_etat, cpt_pseudo) VALUES
('Bonjour à tous !', '2023-05-19', 'A', 'utilisateur1'),
('Qui est disponible pour une réunion demain ?', '2023-05-19', 'A', 'utilisateur2'),
('J\'ai terminé la tâche assignée.', '2023-05-19', 'A', 'utilisateur3');

INSERT INTO t_configuration_conf (conf_nom, conf_description) VALUES
('Configuration 1', 'Description de la configuration 1');

INSERT INTO t_livre_lvr (lvr_titre, lvr_description, lvr_annee, lvr_date, lvr_image, cpt_pseudo) VALUES
('Titre du livre 1', 'Description du livre 1', 2020, '2023-05-19', 'image1.jpg', 'utilisateur1'),
('Titre du livre 2', 'Description du livre 2', 2018, '2023-05-19', 'image2.jpg', 'utilisateur2'),
('Titre du livre 3', 'Description du livre 3', 2021, '2023-05-19', 'image3.jpg', 'utilisateur3');

INSERT INTO t_auteur_aut (aut_nom, aut_prenom, aut_description, aut_etat) VALUES
('Doe', 'John', 'Description de John Doe', 'A'),
('Smith', 'Jane', 'Description de Jane Smith', 'A'),
('Johnson', 'Alice', 'Description d\'Alice Johnson', 'I');

INSERT INTO t_genre_gnr (gnr_nom, gnr_etat) VALUES
('Romance', 'A'),
('Science-fiction', 'A'),
('Mystère', 'A');

INSERT INTO t_ecrit_ecr (lvr_id, aut_id) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO t_type_typ (lvr_id, gnr_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1),
(3, 3);


