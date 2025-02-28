<?php
session_start();
include "bdd.php"; 

if (!isset($_SESSION['id'])) {
    die("Utilisateur non connecté.");
}

$user_id = $_SESSION['id'];

if (!isset($_POST['comment_id'], $_POST['new_comment'], $_POST['film_id'])) {
    die("Données manquantes.");
}

$comment_id = intval($_POST['comment_id']);
$film_id = intval($_POST['film_id']);
$new_comment = trim($_POST['new_comment']);

if (empty($new_comment)) {
    die("Le commentaire ne peut pas être vide.");
}

// Vérification si l'utilisateur est propriétaire du commentaire
$stmt = $pdo->prepare("SELECT user_id FROM comments WHERE comment_id = ?");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment || $comment['user_id'] != $user_id) {
    die("Vous ne pouvez modifier que vos propres commentaires.");
}

// Met à jour le commentaire avec la date de modification
$updateStmt = $pdo->prepare("UPDATE comments SET comment = ?, updated_at = NOW() WHERE comment_id = ?");
$updateStmt->execute([$new_comment, $comment_id]);

// Redirection après modification
header("Location: film.php?id=$film_id");
exit;
?>
