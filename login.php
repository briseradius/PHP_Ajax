<?php 
include 'header.php';
session_start(); // Toujours démarrer la session au début

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['mail'], $_POST['psw'])) {
        header("location: login.php?status=erreur&message=Champs requis");
        exit;
    }

    $mail = trim($_POST['mail']);
    $password = $_POST['psw'];

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("location: login.php?status=erreur&message=Email invalide");
        exit;
    }

    try {
        // Préparation de la requête
        $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = ?");
        $stmt->execute([$mail]); // Exécution avec les valeurs

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
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage()); // Journaliser l'erreur sans l'afficher à l'utilisateur
        header("Location: login.php?status=erreur&message=Problème technique");
        exit;
    }
}
?>

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
</html>
