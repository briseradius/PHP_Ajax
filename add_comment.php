<?php
header('Content-Type: application/json');

session_start();
include "bdd.php"; 

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

if (!isset($_POST['film_id'], $_POST['comment']) || empty(trim($_POST['comment']))) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$film_id = $_POST['film_id'];
$user_id = $_SESSION['id'];
$comment = htmlspecialchars($_POST['comment']);

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
header("location:film.php?id=$film_id");