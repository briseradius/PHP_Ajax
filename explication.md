

$$\ $$\             $$\            $$$$$$\  $$\ $$\                             $$\                 
$$ |\__|            $$ |          $$  __$$\ \__|$$ |                            $$ |                
$$ |$$\  $$$$$$$\ $$$$$$\         $$ /  \__|$$\ $$ |$$$$$$\$$$$\       $$$$$$\  $$$$$$$\   $$$$$$\  
$$ |$$ |$$  _____|\_$$  _|        $$$$\     $$ |$$ |$$  _$$  _$$\     $$  __$$\ $$  __$$\ $$  __$$\ 
$$ |$$ |\$$$$$$\    $$ |          $$  _|    $$ |$$ |$$ / $$ / $$ |    $$ /  $$ |$$ |  $$ |$$ /  $$ |
$$ |$$ | \____$$\   $$ |$$\       $$ |      $$ |$$ |$$ | $$ | $$ |    $$ |  $$ |$$ |  $$ |$$ |  $$ |
$$ |$$ |$$$$$$$  |  \$$$$  |      $$ |      $$ |$$ |$$ | $$ | $$ |$$\ $$$$$$$  |$$ |  $$ |$$$$$$$  |
\__|\__|\_______/    \____/$$$$$$\\__|      \__|\__|\__| \__| \__|\__|$$  ____/ \__|  \__|$$  ____/ 
                           \______|                                   $$ |                $$ |      
                                                                      $$ |                $$ |      
                                                                      \__|                \__|      



# Lisez-moi : Explication du script list_film.php

## Introduction

Ce script a pour objectif d'afficher une liste de films populaires sur une page web, en permettant à l'utilisateur de filtrer par genre et de rechercher des films par titre. Le contenu des films est récupéré à partir de l'API The Movie Database (TMDb). Le script vérifie aussi si l'utilisateur est majeur pour décider si des films adultes doivent être affichés.

## Partie PHP

1. **Inclusions des fichiers :**
   ```php
   include "header.php";
   include 'session.php';
   include "navbar.php";

Ces lignes incluent des fichiers externes dans la page. Ces fichiers contiennent probablement des éléments communs, comme l'en-tête (header.php), la gestion de la session de l'utilisateur (session.php), et la barre de navigation (navbar.php).

    Vérification de l'âge de l'utilisateur :

    <?php if( isset($_SESSION['age'])){
        echo "const age = ". $_SESSION['age'] .";";
    }
    ?>

    Cette partie PHP vérifie si une session contenant l'âge de l'utilisateur existe. Si oui, la variable JavaScript age est définie avec cette valeur, ce qui permettra de savoir si l'utilisateur est majeur ou non (18 ans et plus). Cela permet d'afficher ou non des films pour adultes.

Partie HTML

Le corps de la page HTML est structuré avec Bootstrap pour la mise en page. Il est divisé en deux colonnes :

    Une colonne large qui contient les films.
    Une autre pour afficher la liste des genres de films.

Structure de la page :

    Conteneur principal :

    <div class="container">
        <div class="row">
            <div class="col-10">
                <div class="row" id="film-card-anchor"></div>
            </div>
            <div class="col-2 overflow-auto">
                <h6 class="text-secondary">Recherche par genre</h6>
                <ul class="list-group" id="genre_list"></ul>
            </div>
        </div>
    </div>

    Le contenu est divisé en deux sections :
        Une pour afficher les films.
        Une autre pour afficher la liste des genres.

Partie JavaScript

    Initialisation des variables :

let isAdult = false;

<?php if( isset($_SESSION['age'])){
    echo "const age = ". $_SESSION['age'] .";";
}
?>

if (typeof age !== "undefined" && age >= 18) {
    isAdult = true;
}

La variable isAdult est initialisée à false. Ensuite, si l'âge de l'utilisateur est disponible et supérieur ou égal à 18, la variable isAdult devient true, ce qui permet d'afficher des films pour adultes.

Obtention des genres de films :

function getGenres(url) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            genres = data.genres;
            genreList.innerHTML = genres.map(genre => 
                `<li class="list-group-item"><a class="genres" href="#" data-genre="${genre.id}">${genre.name}</a></li>`
            ).join('');
            configLink(document.getElementsByClassName("genres"));
        });
}

Cette fonction fait une requête à l'API pour récupérer la liste des genres de films. Elle crée ensuite une liste HTML avec les genres récupérés et permet de filtrer les films en cliquant sur un genre.

Affichage des films :

function listFilm(url) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            filmAnchor.innerHTML = data.results.map(result => {
                const genre_list = result.genre_ids.map(id => {
                    const genre = genres.find(g => g.id === id);
                    return genre ? `<a class="card-link card_genres_link" data-genres="${genre.id}" href="#">${genre.name}</a>` : "";
                }).join(" ");
                
                const synopsis = result.overview.length > 255 ? result.overview.slice(0, 255) + "..." : result.overview;
                const imageFilm = result.backdrop_path ? `<img src="https://image.tmdb.org/t/p/w500/${result.backdrop_path}" class="card-img-top" alt="affiche de ${result.title}">` : `<img src="img/th.jpg" class="card-img-top" alt="img indisponible">`;

                return `<div class="col-4 my-2">
                            <div class="card">
                                <a href="film.php?id=${result.id}" class="stretched-link">
                                    ${imageFilm}
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="film.php?id=${result.id}">${result.title}</a></h5>
                                    <p class="card-text h6">${synopsis}</p>
                                    ${genre_list}
                                </div>
                            </div>
                        </div>`;
            }).join('');
            configLink(document.getElementsByClassName('card_genres_link'));
        });
}

Cette fonction fait une requête à l'API pour récupérer les films populaires. Elle affiche chaque film avec son titre, sa couverture, un extrait du synopsis et ses genres. Si un film possède une image, elle sera affichée, sinon une image par défaut sera utilisée.

Gestion du filtrage des films par genre :

function configLink(links) {
    [...links].forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const id = link.dataset.genre;
            listFilm(`https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&language=fr&with_genres=${id}&include_adult=${isAdult}`);
        });
    });
}

Cette fonction permet de filtrer les films en fonction du genre sélectionné. Lorsqu'un genre est cliqué, une nouvelle requête est envoyée à l'API pour obtenir les films correspondants à ce genre.

Recherche de films par titre :

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = search.value.trim();
        if (query.length > 0) {
            const searchUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&language=fr&query=${encodeURIComponent(query)}&include_adult=${isAdult}`;
            listFilm(searchUrl);
        }
    });

    Cette fonction permet de rechercher des films en fonction du titre entré dans un formulaire de recherche. Lorsque l'utilisateur soumet le formulaire, une requête est envoyée à l'API pour obtenir les films correspondant à la recherche.

Conclusion

Ce script permet de récupérer et d'afficher des films à partir de l'API TMDb. Il inclut des fonctionnalités comme le filtrage par genre, la recherche par titre, et l'affichage conditionnel des films pour adultes en fonction de l'âge de l'utilisateur. Ce script utilise principalement JavaScript pour interagir avec l'API et mettre à jour dynamiquement le contenu de la page, tout en utilisant PHP pour gérer l'âge de l'utilisateur côté serveur.



$$\                     $$\                         $$\                 
$$ |                    \__|                        $$ |                
$$ | $$$$$$\   $$$$$$\  $$\ $$$$$$$\       $$$$$$\  $$$$$$$\   $$$$$$\  
$$ |$$  __$$\ $$  __$$\ $$ |$$  __$$\     $$  __$$\ $$  __$$\ $$  __$$\ 
$$ |$$ /  $$ |$$ /  $$ |$$ |$$ |  $$ |    $$ /  $$ |$$ |  $$ |$$ /  $$ |
$$ |$$ |  $$ |$$ |  $$ |$$ |$$ |  $$ |    $$ |  $$ |$$ |  $$ |$$ |  $$ |
$$ |\$$$$$$  |\$$$$$$$ |$$ |$$ |  $$ |$$\ $$$$$$$  |$$ |  $$ |$$$$$$$  |
\__| \______/  \____$$ |\__|\__|  \__|\__|$$  ____/ \__|  \__|$$  ____/ 
              $$\   $$ |                  $$ |                $$ |      
              \$$$$$$  |                  $$ |                $$ |      
               \______/                   \__|                \__|      




# Explication du script de connexion PHP

## Introduction

Ce script PHP gère la connexion d'un utilisateur sur un site. L'utilisateur doit entrer un email et un mot de passe. Le script vérifie la validité de ces informations, les compare à celles stockées dans la base de données, et crée une session si l'utilisateur est authentifié avec succès. En cas d'erreur, un message est renvoyé à l'utilisateur.

## Partie PHP

### 1. **Démarrage de la session**
   session_start();  Toujours démarrer la session au début

La fonction session_start() est utilisée pour démarrer une session PHP. Cela permet de stocker et de récupérer des informations liées à l'utilisateur, comme son ID, son rôle, et son état de connexion.
2. Vérification de la méthode POST

if ($_SERVER["REQUEST_METHOD"] === "POST") {

Ce bloc vérifie si la requête envoyée est de type POST. Cela signifie que le formulaire de connexion a été soumis.
3. Validation des champs requis

if (!isset($_POST['mail'], $_POST['psw'])) {
    header("location: login.php?status=erreur&message=Champs requis");
    exit;
}

Le script vérifie si les champs mail et psw (mot de passe) ont bien été envoyés via le formulaire. Si l'un des champs est manquant, l'utilisateur est redirigé vers la page de connexion avec un message d'erreur.
4. Sanitisation et validation de l'email

$mail = trim($_POST['mail']);
$password = $_POST['psw'];

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    header("location: login.php?status=erreur&message=Email invalide");
    exit;
}

L'email est d'abord "nettoyé" avec trim() pour supprimer les espaces superflus. Ensuite, filter_var() est utilisé pour vérifier si l'email est valide. Si l'email est invalide, l'utilisateur est redirigé avec un message d'erreur.
5. Requête SQL pour vérifier les informations de l'utilisateur

try {
    // Préparation de la requête
    $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = ?");
    $stmt->execute([$mail]); // Exécution avec les valeurs

Le script utilise PDO pour interroger la base de données et vérifier si un utilisateur existe avec l'email fourni. La requête est préparée pour éviter les attaques par injection SQL. L'email est passé comme paramètre sécurisé.
6. Vérification des identifiants de l'utilisateur

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['psw'])) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['role'] = $user['user_role'];
    $_SESSION['login'] = true;
    $_SESSION['age'] = $user['age'];
    $_SESSION['fullname'] = $user['firstname'] . ' ' . $user['lastname'];
    
    header("Location: index.php");
    exit;
} else {
    header("Location: login.php?status=erreur&message=Identifiants invalides");
    exit;
}

Une fois que les données de l'utilisateur sont récupérées, le mot de passe est vérifié avec la fonction password_verify(), qui compare le mot de passe fourni avec le mot de passe haché stocké dans la base de données.

Si les identifiants sont valides, plusieurs informations de l'utilisateur sont enregistrées dans la session (id, role, age, fullname). L'utilisateur est ensuite redirigé vers la page d'accueil.

Si les identifiants sont invalides, l'utilisateur est redirigé vers la page de connexion avec un message d'erreur.
7. Gestion des erreurs SQL

} catch (PDOException $e) {
    error_log("Erreur SQL : " . $e->getMessage()); // Journaliser l'erreur sans l'afficher à l'utilisateur
    header("Location: login.php?status=erreur&message=Problème technique");
    exit;
}

En cas d'erreur lors de l'exécution de la requête SQL, l'exception PDOException est capturée. L'erreur est enregistrée dans le journal des erreurs, mais l'utilisateur reçoit un message générique l'informant d'un problème technique.
Partie HTML
1. Formulaire de connexion

<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row h-100 justify-content-center align-items-center">
            <form action="login.php" method="POST" class="text-center">
                <div class="col-12">
                    <input type="email" placeholder="Email" name="mail" required>
                </div>
                <div class="col-12">
                    <input type="password" placeholder="Mot de passe" name="psw" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

Le formulaire HTML permet à l'utilisateur de saisir son email et son mot de passe pour se connecter. Il utilise la méthode POST pour envoyer les données au script PHP.

    Champ email : Permet à l'utilisateur de saisir son adresse email.
    Champ mot de passe : Permet à l'utilisateur de saisir son mot de passe.
    Bouton de soumission : Lorsque l'utilisateur clique sur ce bouton, le formulaire est envoyé au script PHP pour traitement.

Conclusion

Ce script PHP permet de gérer la connexion des utilisateurs de manière sécurisée. Il effectue des vérifications sur les données soumises par l'utilisateur, vérifie les identifiants dans la base de données et démarre une session si l'utilisateur est authentifié avec succès. En cas d'erreur, il renvoie des messages appropriés pour informer l'utilisateur. La sécurité est assurée en utilisant des requêtes préparées avec PDO et en vérifiant les mots de passe avec password_verify().



 $$$$$$\  $$\ $$\                             $$\                 
$$  __$$\ \__|$$ |                            $$ |                
$$ /  \__|$$\ $$ |$$$$$$\$$$$\       $$$$$$\  $$$$$$$\   $$$$$$\  
$$$$\     $$ |$$ |$$  _$$  _$$\     $$  __$$\ $$  __$$\ $$  __$$\ 
$$  _|    $$ |$$ |$$ / $$ / $$ |    $$ /  $$ |$$ |  $$ |$$ /  $$ |
$$ |      $$ |$$ |$$ | $$ | $$ |    $$ |  $$ |$$ |  $$ |$$ |  $$ |
$$ |      $$ |$$ |$$ | $$ | $$ |$$\ $$$$$$$  |$$ |  $$ |$$$$$$$  |
\__|      \__|\__|\__| \__| \__|\__|$$  ____/ \__|  \__|$$  ____/ 
                                    $$ |                $$ |      
                                    $$ |                $$ |      
                                    \__|                \__|      

# Explication du script de gestion des commentaires pour un film

## Introduction

Ce script PHP permet d'afficher les informations détaillées d'un film, ainsi que les commentaires associés. Les utilisateurs peuvent ajouter, modifier ou supprimer leurs propres commentaires, ou ceux d'autres utilisateurs s'ils sont administrateurs. Le script récupère les informations du film depuis une API externe et gère l'affichage des commentaires depuis la base de données locale.

## Partie PHP

### 1. **Inclusions des fichiers nécessaires**
   ```php
   include "header.php";
   include "session.php";
   include "navbar.php";

Le script commence par inclure les fichiers header.php, session.php, et navbar.php, qui gèrent respectivement l'en-tête de la page, l'initialisation de la session utilisateur, et la barre de navigation.
2. Vérification de l'ID du film

if (empty($_GET['id'])) {
    die("ID du film manquant.");
}

$movie_id = intval($_GET['id']); // Sécurisation de l'ID

Le script vérifie si l'ID du film est fourni dans l'URL ($_GET['id']). Si ce n'est pas le cas, une erreur est générée. L'ID est ensuite converti en entier pour éviter toute injection ou manipulation malveillante.
3. Récupération des informations de l'utilisateur et de l'API

$user_id = $_SESSION['id'] ?? null; // Récupération de l'ID utilisateur
$user_role = $_SESSION['role'] ?? ''; // Récupération du rôle (admin ou user)

$apiKey = '245d54f855207f523fb1b30b39175326';
$urlFilm = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$apiKey&language=fr";

Le script récupère l'ID utilisateur et son rôle à partir de la session, si disponibles. Ensuite, il génère l'URL pour interroger l'API externe (The Movie Database) en utilisant l'ID du film et une clé API pour récupérer les informations du film.
4. Récupération des informations du film depuis l'API

$filmData = json_decode(file_get_contents($urlFilm), true);

if (!$filmData) {
    die("Film introuvable.");
}

Le script interroge l'API pour récupérer les informations du film sous forme de JSON. Si l'API ne renvoie pas de données valides, une erreur est affichée.
5. Récupération des commentaires pour ce film

$comments = $pdo->prepare("
    SELECT c.user_id, c.comment, 
           DATE_FORMAT(c.created_at, '%d/%m/%Y %H:%i') AS created_at, 
           DATE_FORMAT(c.updated_at, '%H:%i') AS updated_at, 
           c.user_id, u.firstname, u.lastname 
    FROM comments c 
    JOIN user u ON c.user_id = u.id 
    WHERE c.movie_id = ? 
    ORDER BY c.created_at DESC
");
$comments->execute([$movie_id]);
$commentsList = $comments->fetchAll();

Le script prépare une requête SQL pour récupérer les commentaires associés au film spécifié. Il inclut des informations sur l'utilisateur (prénom, nom), ainsi que des dates de création et de mise à jour formatées en français.
Partie HTML
1. Affichage des informations du film

<h1 class="mt-4"><?php echo htmlspecialchars($filmData['title']); ?></h1>
<img src="https://image.tmdb.org/t/p/w500/<?php echo $filmData['backdrop_path']; ?>" class="img-fluid" alt="Affiche">
<p class="mt-3"><?php echo htmlspecialchars($filmData['overview']); ?></p>

Le titre, l'affiche du film et une brève description sont affichés à l'utilisateur. Les données sont sécurisées avec htmlspecialchars() pour éviter les attaques XSS (Cross-Site Scripting).
2. Affichage des commentaires

<h3>Commentaires</h3>
<?php foreach ($commentsList as $comment): ?>
    <div class="comment-box">
        <p>
            <small class="text-muted">
                Posté le <?php echo htmlspecialchars($comment['created_at']); ?>  
                <?php if (!empty($comment['updated_at'])): ?>
                    - <span class="text-warning">Modifié à <?php echo htmlspecialchars($comment['updated_at']); ?></span>
                <?php endif; ?>
            </small><br>
            <strong><?php echo htmlspecialchars($comment['lastname']) . " " . htmlspecialchars($comment['firstname']); ?>:</strong> 
            <?php echo htmlspecialchars($comment['comment']); ?>
        </p>

Les commentaires sont affichés dans un format lisible, avec la date de création et de modification (si applicable). Le nom de l'utilisateur est également affiché avant son commentaire.
3. Modification et suppression des commentaires

<?php if ($user_id == $comment['user_id'] || $user_role === 'admin'): ?>
    <form action="delete_comment.php" method="POST" style="display:inline;">
        <input type="hidden" name="comment_id" value="<?php echo $comment['user_id']; ?>">
        <input type="hidden" name="film_id" value="<?php echo $movie_id; ?>">
        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
    </form>

    <?php if ($user_id == $comment['user_id']): ?>
        <button class="btn btn-warning btn-sm edit-btn" 
                data-comment-id="<?php echo $comment['user_id']; ?>" 
                data-comment="<?php echo htmlspecialchars($comment['comment']); ?>">
            Modifier
        </button>
    <?php endif; ?>
<?php endif; ?>

Si l'utilisateur est l'auteur du commentaire ou un administrateur, des boutons "Modifier" et "Supprimer" sont affichés. Le bouton "Modifier" affiche un formulaire de modification caché. Le bouton "Supprimer" supprime le commentaire de la base de données via un formulaire POST.
4. Ajout d'un commentaire

<?php if ($user_id): ?>
    <form action="add_comment.php" method="POST" id="add-form">
        <input type="hidden" name="film_id" value="<?php echo $movie_id; ?>">
        <textarea name="comment" class="form-control" placeholder="Ajoutez un commentaire" required></textarea>
        <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Connectez-vous</a> pour commenter.</p>
<?php endif; ?>

Si l'utilisateur est connecté, un formulaire d'ajout de commentaire est affiché. Sinon, un lien pour se connecter est proposé.
5. Formulaire de modification caché

<div id="edit-form" style="display:none;">
    <form action="edit_comment.php" method="POST">
        <input type="hidden" name="comment_id" id="edit-comment-id">
        <input type="hidden" name="film_id" value="<?php echo $movie_id; ?>">
        <textarea name="new_comment" id="edit-comment-text" class="form-control" required></textarea>
        <button type="submit" class="btn btn-success mt-2">Modifier</button>
        <button type="button" class="btn btn-secondary mt-2" id="cancel-edit">Annuler</button>
    </form>
</div>

Ce formulaire est caché par défaut et permet de modifier un commentaire. Il est affiché via JavaScript lorsque l'utilisateur clique sur le bouton "Modifier".
Partie JavaScript
1. Affichage du formulaire de modification

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('add-form').style.display = 'none';
        document.getElementById('edit-form').style.display = 'block';
        document.getElementById('edit-comment-id').value = this.dataset.commentId;
        document.getElementById('edit-comment-text').value = this.dataset.comment;
    });
});

Ce script permet d'afficher le formulaire de modification lorsque l'utilisateur clique sur le bouton "Modifier". Il pré-remplit le formulaire avec les données du commentaire sélectionné.
2. Annulation de l'édition

document.getElementById('cancel-edit').addEventListener('click', function() {
    document.getElementById('edit-form').style.display = 'none';
    document.getElementById('add-form').style.display = 'block';
});

Ce script permet d'annuler l'édition d'un commentaire en réaffichant le formulaire d'ajout de commentaire et en masquant le formulaire de modification.
Conclusion

Ce script PHP permet de gérer l'affichage, l'ajout, la modification et la suppression de commentaires pour un film. Il utilise des fonctionnalités avancées de PHP pour interroger une API externe et gérer les données des utilisateurs. La gestion des commentaires est sécurisée et permet une interaction riche entre les utilisateurs du site.



                $$\       $$\                                                                            $$\     
                $$ |      $$ |                                                                           $$ |    
 $$$$$$\   $$$$$$$ | $$$$$$$ |       $$$$$$$\  $$$$$$\  $$$$$$\$$$$\  $$$$$$\$$$$\   $$$$$$\  $$$$$$$\ $$$$$$\   
 \____$$\ $$  __$$ |$$  __$$ |      $$  _____|$$  __$$\ $$  _$$  _$$\ $$  _$$  _$$\ $$  __$$\ $$  __$$\\_$$  _|  
 $$$$$$$ |$$ /  $$ |$$ /  $$ |      $$ /      $$ /  $$ |$$ / $$ / $$ |$$ / $$ / $$ |$$$$$$$$ |$$ |  $$ | $$ |    
$$  __$$ |$$ |  $$ |$$ |  $$ |      $$ |      $$ |  $$ |$$ | $$ | $$ |$$ | $$ | $$ |$$   ____|$$ |  $$ | $$ |$$\ 
\$$$$$$$ |\$$$$$$$ |\$$$$$$$ |      \$$$$$$$\ \$$$$$$  |$$ | $$ | $$ |$$ | $$ | $$ |\$$$$$$$\ $$ |  $$ | \$$$$  |
 \_______| \_______| \_______|$$$$$$\\_______| \______/ \__| \__| \__|\__| \__| \__| \_______|\__|  \__|  \____/ 
                              \______|                                                                           
                                                                                                                 
                                                                                                                 

# Explication du script d'ajout de commentaire

## Introduction

Ce script PHP permet aux utilisateurs connectés d'ajouter un commentaire sur un film spécifique. Les données sont envoyées via une requête POST et sont enregistrées dans une base de données. Si la requête est valide, un message de succès avec les détails du commentaire est renvoyé sous forme de JSON. Si des erreurs surviennent, une erreur est renvoyée également sous forme de JSON.

## Détails du fonctionnement du script

### 1. **Configuration de l'en-tête et démarrage de la session**
   ```php
   header('Content-Type: application/json');
   session_start();
   include "bdd.php";

    Le script commence par définir le type de contenu comme JSON afin de renvoyer les réponses au format JSON.
    Il initialise ensuite la session PHP, ce qui permet de gérer l'authentification de l'utilisateur et ses données.
    Enfin, il inclut le fichier bdd.php pour établir la connexion à la base de données via PDO.

2. Vérification de l'utilisateur connecté

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

Le script vérifie si l'utilisateur est connecté en recherchant un id utilisateur dans la session. Si l'utilisateur n'est pas connecté, un message d'erreur est renvoyé en JSON.
3. Validation des données envoyées

if (!isset($_POST['film_id'], $_POST['comment']) || empty(trim($_POST['comment']))) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

Le script vérifie si les données nécessaires (film_id et comment) sont présentes et non vides. Si l'une de ces conditions échoue, une erreur est renvoyée.
4. Récupération des données du formulaire

$film_id = $_POST['film_id'];
$user_id = $_SESSION['id'];
$comment = htmlspecialchars($_POST['comment']);

Le script récupère l'ID du film, l'ID de l'utilisateur (depuis la session) et le commentaire (qui est sécurisé par htmlspecialchars pour éviter les attaques XSS).
5. Insertion du commentaire dans la base de données

try {
    $stmt = $pdo->prepare("INSERT INTO comments (movie_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$film_id, $user_id, $comment]);

    echo json_encode([
        'success' => true,
        'comment' => [
            'movieid' => $film_id,
            'user_id' => $user_id,
            'comment' => $comment,
            'created_at' => date("Y-m-d H:i:s")
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur SQL: ' . $e->getMessage()]);
}

Le script tente d'insérer le commentaire dans la table comments de la base de données. Les valeurs du film, de l'utilisateur, du commentaire et la date actuelle sont insérées. Si l'insertion réussit, un message de succès est renvoyé en JSON, incluant les détails du commentaire inséré. Si une erreur SQL survient, un message d'erreur est renvoyé.
6. Redirection vers la page du film

header("location:film.php?id=$film_id");

Après l'exécution du script, l'utilisateur est redirigé vers la page du film spécifié, où il peut voir le commentaire ajouté.
Conclusion

Ce script permet de gérer l'ajout de commentaires pour les films en toute sécurité. Il vérifie que l'utilisateur est connecté, que les données envoyées sont valides et enregistre les commentaires dans la base de données. Si tout se passe bien, un message de succès est renvoyé en JSON, et l'utilisateur est redirigé vers la page du film. En cas d'erreur, un message d'erreur détaillant la cause de l'échec est retourné.




$$$$$$$$\                              $$\     $$\                               
$$  _____|                             $$ |    \__|                              
$$ |    $$$$$$\  $$$$$$$\   $$$$$$$\ $$$$$$\   $$\  $$$$$$\  $$$$$$$\   $$$$$$$\ 
$$$$$\ $$  __$$\ $$  __$$\ $$  _____|\_$$  _|  $$ |$$  __$$\ $$  __$$\ $$  _____|
$$  __|$$ /  $$ |$$ |  $$ |$$ /        $$ |    $$ |$$ /  $$ |$$ |  $$ |\$$$$$$\  
$$ |   $$ |  $$ |$$ |  $$ |$$ |        $$ |$$\ $$ |$$ |  $$ |$$ |  $$ | \____$$\ 
$$ |   \$$$$$$  |$$ |  $$ |\$$$$$$$\   \$$$$  |$$ |\$$$$$$  |$$ |  $$ |$$$$$$$  |
\__|    \______/ \__|  \__| \_______|   \____/ \__| \______/ \__|  \__|\_______/ 
                                                                                 
                                                                                 
                                                                                 


#  Fonctions natives utilisées dans les scripts

Ce fichier répertorie et classe par langage toutes les fonctions natives utilisées dans les scripts PHP fournis, avec une brève explication pour chaque fonction.

## PHP

### 1. **session_start()**
   - **Description** : Cette fonction démarre une nouvelle session ou reprend une session existante. Elle est nécessaire pour accéder aux variables de session.
   - **Exemple** : `session_start();`

### 2. **header()**
   - **Description** : La fonction `header()` envoie un en-tête HTTP brut au navigateur. Cela est utile pour la redirection ou la définition de types de contenu.
   - **Exemple** : `header('Content-Type: application/json');`

### 3. **isset()**
   - **Description** : Vérifie si une variable est définie et n'est pas `null`.
   - **Exemple** : `isset($_POST['film_id']);`

### 4. **empty()**
   - **Description** : Vérifie si une variable est vide (c'est-à-dire, si elle est soit `false`, soit `0`, soit une chaîne vide, etc.).
   - **Exemple** : `empty($_POST['comment']);`

### 5. **trim()**
   - **Description** : Supprime les espaces blancs (ou autres caractères) en début et fin d'une chaîne de caractères.
   - **Exemple** : `trim($_POST['comment']);`

### 6. **htmlspecialchars()**
   - **Description** : Convertit les caractères spéciaux en entités HTML. Cela permet d'éviter les attaques de type XSS (Cross-Site Scripting).
   - **Exemple** : `htmlspecialchars($_POST['comment']);`

### 7. **json_encode()**
   - **Description** : Convertit une variable PHP en une chaîne JSON. Utile pour l'échange de données entre le serveur et le client.
   - **Exemple** : `json_encode(['success' => true]);`

### 8. **PDO::prepare()**
   - **Description** : Prépare une requête SQL avant son exécution, ce qui permet de l'exécuter de manière sécurisée en utilisant des paramètres liés.
   - **Exemple** : `$stmt = $pdo->prepare("INSERT INTO comments (movie_id, user_id, comment) VALUES (?, ?, ?)");`

### 9. **PDO::execute()**
   - **Description** : Exécute la requête préparée avec les valeurs des paramètres.
   - **Exemple** : `$stmt->execute([$film_id, $user_id, $comment]);`

### 10. **PDO::fetch()**
   - **Description** : Récupère une ligne de résultat sous forme de tableau associatif.
   - **Exemple** : `$user = $stmt->fetch(PDO::FETCH_ASSOC);`

### 11. **PDO::fetchAll()**
   - **Description** : Récupère toutes les lignes de résultats sous forme de tableau.
   - **Exemple** : `$commentsList = $comments->fetchAll();`

### 12. **date()**
   - **Description** : Formate une date ou une heure sous forme de chaîne de caractères.
   - **Exemple** : `date("Y-m-d H:i:s");`

### 13. **file_get_contents()**
   - **Description** : Lit le contenu d'un fichier dans une chaîne de caractères.
   - **Exemple** : `file_get_contents($urlFilm);`

### 14. **exit()**
   - **Description** : Termine l'exécution du script.
   - **Exemple** : `exit;`

### 15. **die()**
   - **Description** : Arrête l'exécution du script et peut optionnellement afficher un message d'erreur.
   - **Exemple** : `die("ID du film manquant.");`

### 16. **$_SESSION**
   - **Description** : Superglobale qui permet d'accéder aux variables de session.
   - **Exemple** : `$_SESSION['id'];`

### 17. **$_POST**
   - **Description** : Superglobale qui permet d'accéder aux données envoyées via une requête HTTP POST.
   - **Exemple** : `$_POST['comment'];`

### 18. **$_GET**
   - **Description** : Superglobale qui permet d'accéder aux données envoyées via une requête HTTP GET.
   - **Exemple** : `$_GET['id'];`

### 19. **htmlspecialchars()**
   - **Description** : Convertit les caractères spéciaux en entités HTML pour éviter les injections XSS.
   - **Exemple** : `htmlspecialchars($comment['comment']);`

---

## JavaScript

### 1. **document.querySelectorAll()**
   - **Description** : Sélectionne tous les éléments correspondant à un sélecteur CSS donné.
   - **Exemple** : `document.querySelectorAll('.edit-btn');`

### 2. **addEventListener()**
   - **Description** : Attache un gestionnaire d'événements à un élément.
   - **Exemple** : `button.addEventListener('click', function() { /* code */ });`

### 3. **document.getElementById()**
   - **Description** : Sélectionne un élément par son identifiant.
   - **Exemple** : `document.getElementById('edit-form');`

### 4. **style.display**
   - **Description** : Modifie le style d'affichage d'un élément HTML (par exemple, pour masquer ou afficher un élément).
   - **Exemple** : `document.getElementById('add-form').style.display = 'none';`

### 5. **JSON.parse()**
   - **Description** : Analyse une chaîne JSON et la convertit en objet JavaScript.
   - **Exemple** : `JSON.parse(response);`

---

## Conclusion

Ce fichier a pour but de fournir un aperçu des principales fonctions natives PHP et JavaScript utilisées dans les scripts fournis, accompagnées d'une brève explication. Si de nouvelles fonctions sont ajoutées ou si des informations supplémentaires sont nécessaires, il est conseillé de mettre à jour ce fichier.
