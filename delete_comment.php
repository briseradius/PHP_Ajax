<?php
session_start();
include "bdd.php"; 

if (!isset($_SESSION['id'])) {
    die("Utilisateur non connecté.");
}

$user_id = $_SESSION['id'];
$user_role = $_SESSION['role'] ?? 'user';

if (!isset($_POST['comment_id'], $_POST['film_id'])) {
    die("Données manquantes.");
}

$comment_id = intval($_POST['comment_id']);
$film_id = intval($_POST['film_id']);

// Vérification si l'utilisateur est propriétaire ou admin
$stmt = $pdo->prepare("SELECT user_id FROM comments WHERE comment_id = ?");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment) {
    die("Commentaire introuvable.");
}

if ($user_id == $comment['user_id'] || $user_role == 'admin') {
    $deleteStmt = $pdo->prepare("DELETE FROM comments WHERE comment_id = ?");
    $deleteStmt->execute([$comment_id]);
    header("Location: film.php?id=$film_id");
    exit;
} else {
    die("Vous n'avez pas le droit de supprimer ce commentaire.");
}
?>
