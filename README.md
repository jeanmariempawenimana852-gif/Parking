# Parking
Contexte et objectif 
Le système vise à automatiser et optimiser la gestion d'un parking en facilitant l'entrée, le stationnement, le paiement et la sortie des véhicules, tout en fournissant des outils de supervision pour les gestionnaires.

Prérequis

Un ordinateur (Windows/Mac/Linux)
Une connexion Internet
XAMPP installé
Étape 1 : Installation de XAMPP
Télécharger XAMPP

Allez sur https://www.apachefriends.org
Téléchargez la version pour votre système d'exploitation
Choisissez PHP 7.4 ou version supérieure

Installer XAMPP

Exécutez le fichier téléchargé
Suivez l'installation (gardez les options par défaut)
Installez dans C:\xampp (Windows) ou /Applications/XAMPP (Mac)

Cliquez sur le bouton vert "Code"
Sélectionnez "Download ZIP"
Enregistrez le fichier sur votre ordinateur
Extraire le projet

Allez dans votre dossier Téléchargements
Faites un clic droit sur le fichier ZIP → Extraire tout
Copiez le dossier extrait
Collez-le dans C:\xampp\htdocs\ (Windows) ou /Applications/XAMPP/htdocs/ (Mac)
Renommez le dossier en parking (pour simplifier)

Méthode 2 : Avec Git (Recommandé)

Installer Git

Téléchargez Git depuis https://git-scm.com
Installez-le avec les options par défaut

Cloner le projet

Ouvrez l'invite de commandes (CMD) ou Terminal
Naviguez vers htdocs :

bash     cd C:\xampp\htdocs

Clonez le projet :

bash     git clone https://github.com/[nom-utilisateur]/[nom-projet].git parking

Étape 3 : Démarrer les Services XAMPP

Ouvrez le Panneau de contrôle XAMPP
Cliquez sur "Start" à côté de Apache
Cliquez sur "Start" à côté de MySQL
Les boutons deviennent verts → Les services sont actifs


Étape 4 : Créer la Base de Données

Accéder à phpMyAdmin

Ouvrez votre navigateur
Tapez : http://localhost/phpmyadmin
Appuyez sur Entrée


Créer une nouvelle base de données

Cliquez sur "Nouvelle base de données" (ou "New")
Nom : parking_db (vérifiez le nom dans le fichier README du projet)
Interclassement : utf8mb4_general_ci
Cliquez sur "Créer"


Importer le fichier SQL

Sélectionnez la base de données parking_db dans le menu de gauche
Cliquez sur l'onglet "Importer"
Cliquez sur "Choisir un fichier"
Naviguez vers : C:\xampp\htdocs\parking\database\ ou \sql\
Sélectionnez le fichier .sql (ex: parking.sql, schema.sql, database.sql)
Cliquez sur "Exécuter" en bas de page
Attendez le message de succès

Étape 5 : Configurer la Connexion à la Base de Données

Ouvrir le dossier du projet

Allez dans C:\xampp\htdocs\parking\

Trouver le fichier de configuration dans admin/includes

Cherchez un fichier nommé :

dbconnexion.php
Ou dans un dossier config/ ou includes/





