# Projet d'Apprentissage PHP, Requêtes Asynchrones et Bases de Données

## Description
Ce projet est un exercice destiné aux développeurs web souhaitant apprendre et pratiquer le langage PHP, les requêtes asynchrones en JavaScript et la gestion d'une base de données avec MySQL.

Le projet consiste à afficher des informations sur des films en récupérant des données depuis une API (*The Movie Database - TMDb*) et à permettre aux utilisateurs d'ajouter, modifier et supprimer des commentaires sur ces films.

---

## Fonctionnalités principales
- Affichage des détails d'un film via l'API TMDb
- Ajout de commentaires par les utilisateurs connectés
- Modification et suppression des commentaires par leur propriétaire
- Suppression des commentaires par un administrateur
- Utilisation de requêtes asynchrones pour l'ajout et la modification des commentaires
- Système de connexion et de session utilisateur

---

## Technologies utilisées
- **Back-end** : PHP (PDO pour les requêtes SQL)
- **Front-end** : HTML, CSS, JavaScript (AJAX)
- **Base de données** : MySQL
- **API externe** : TMDb pour la récupération des données des films

---

## Installation
### Prérequis
- Un serveur local (XAMPP, WAMP ou MAMP)
- PHP 7+
- MySQL

### 1. Cloner le projet
```sh
https://github.com/votre-repo/projet_film.git
cd projet_film
```

### 2. Importer la base de données
- Créer une base de données nommée `film_db`
- Importer le fichier `database.sql` dans MySQL

### 3. Configurer la connexion à la base de données
Modifier le fichier `bdd.php` avec vos informations MySQL :
```php
$host = "localhost";
$dbname = "film_db";
$username = "root";
$password = "";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
```

### 4. Lancer le projet
- Démarrer votre serveur Apache et MySQL
- Accéder au projet via : `http://localhost/projet_film/`

---

## Structure du projet
```
projet_flim/
│-- index.php               # Page d'accueil
│-- film.php                # Page d'affichage des films et commentaires
│-- login.php               # Page de connexion
│-- register.php            # Page d'inscription
│-- add_comment.php         # Script d'ajout de commentaire (AJAX)
│-- edit_comment.php        # Script de modification de commentaire
│-- delete_comment.php      # Script de suppression de commentaire
│-- bdd.php                 # Configuration de la base de données
│-- assets/
│   │-- css/
│   │   └── style.css       # Styles CSS
│-- flim.sql            # Fichier de structure de la base de données
```

---

## Utilisation
1. **S'inscrire et se connecter**
2. **Consulter un film** : en accédant à `film.php?id=ID_DU_FILM`
3. **Ajouter un commentaire** : un champ permet de saisir un texte
4. **Modifier un commentaire** : un bouton "Modifier" s'affiche pour l'auteur
5. **Supprimer un commentaire** : un bouton "Supprimer" est disponible pour l'auteur et les administrateurs

---

## Améliorations possibles
- Ajout de la pagination pour les commentaires
- Gestion des likes/dislikes sur les commentaires
- Intégration d'un système de recherche de films

---

## Auteur
Projet d'exercice pour développeurs web souhaitant améliorer leurs compétences en PHP, AJAX et MySQL.

Bon apprentissage ! 🚀

