<?php
include "header.php";
include "session.php";
include "navbar.php";

// Vérifie si l'ID du film est bien fourni
if (empty($_GET['id'])) {
    die("ID du film manquant.");
}

$movie_id = intval($_GET['id']); // Sécurisation de l'ID
$user_id = $_SESSION['id'] ?? null; // Récupération de l'ID utilisateur
$user_role = $_SESSION['role'] ?? ''; // Récupération du rôle (admin ou user)

$apiKey = '245d54f855207f523fb1b30b39175326';
$urlFilm = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$apiKey&language=fr";

// Récupère les informations du film depuis l'API
$filmData = json_decode(file_get_contents($urlFilm), true);

if (!$filmData) {
    die("Film introuvable.");
}

// Récupération des commentaires du film avec formatage de la date en français
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
?>

<body>
    <div class="container">
        <h1 class="mt-4"><?php echo htmlspecialchars($filmData['title']); ?></h1>
        <img src="https://image.tmdb.org/t/p/w500/<?php echo $filmData['backdrop_path']; ?>" class="img-fluid" alt="Affiche">
        <p class="mt-3"><?php echo htmlspecialchars($filmData['overview']); ?></p>

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

                <!-- Affichage des boutons Modifier/Supprimer uniquement pour l'auteur ou l'admin -->
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
            </div>
        <?php endforeach; ?>

        <!-- Formulaire pour ajouter un commentaire -->
        <?php if ($user_id): ?>
            <form action="add_comment.php" method="POST" id="add-form">
                <input type="hidden" name="film_id" value="<?php echo $movie_id; ?>">
                <textarea name="comment" class="form-control" placeholder="Ajoutez un commentaire" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Connectez-vous</a> pour commenter.</p>
        <?php endif; ?>

        <!-- Formulaire de modification caché -->
        <div id="edit-form" style="display:none;">
            <form action="edit_comment.php" method="POST">
                <input type="hidden" name="comment_id" id="edit-comment-id">
                <input type="hidden" name="film_id" value="<?php echo $movie_id; ?>">
                <textarea name="new_comment" id="edit-comment-text" class="form-control" required></textarea>
                <button type="submit" class="btn btn-success mt-2">Modifier</button>
                <button type="button" class="btn btn-secondary mt-2" id="cancel-edit">Annuler</button>
            </form>
        </div>
    </div>

    <script>
        // Affichage du formulaire de modification
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('add-form').style.display = 'none';
                document.getElementById('edit-form').style.display = 'block';
                document.getElementById('edit-comment-id').value = this.dataset.commentId;
                document.getElementById('edit-comment-text').value = this.dataset.comment;
            });
        });

        // Annulation de l'édition
        document.getElementById('cancel-edit').addEventListener('click', function() {
            document.getElementById('edit-form').style.display = 'none';
            document.getElementById('add-form').style.display = 'block';
        });
    </script>
</body>
</html>
