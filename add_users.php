<?php
include 'bdd.php';
include 'header.php';

if (!empty($_POST['mail']) && !empty($_POST['psw']) && !empty($_POST['age']) && !empty($_POST['lastname']) && !empty($_POST['firstname'])) {

    // Récupération des données du formulaire
    $mail = $_POST['mail'];
    $birthdate = $_POST['age']; // Contient la date de naissance (format YYYY-MM-DD)

    // Calcul de l'âge à partir de la date de naissance
    $today = new DateTime(); // Date actuelle
    $birthDateObj = new DateTime($birthdate); // Conversion de la date de naissance en objet DateTime
    $age = $today->diff($birthDateObj)->y; // Calcul de la différence en années

    // Vérification si l'utilisateur existe déjà dans la base de données
    $verifUser = $pdo->query('SELECT COUNT(*) FROM user WHERE mail= "' . $mail . '"');
    $verifUserNbs = $verifUser->fetch();

    if ($verifUserNbs[0] == 0) { // Si l'utilisateur n'existe pas déjà
        try {
            // Hashage du mot de passe avant l'insertion dans la base de données
            $psw = password_hash($_POST['psw'], PASSWORD_ARGON2I);
            $role = '';

            // Préparation et exécution de la requête d'insertion
            $addU = $pdo->prepare("INSERT INTO user (age, lastname, firstname, user_role, psw , mail) VALUES (?, ?, ?, ?, ?, ?)");
            $addUser = $addU->execute([$age, $_POST['lastname'], $_POST['firstname'], $role, $psw, $mail]);

            // Redirection après enregistrement réussi
            header("location:index.php?status=Ok&message=Enregistrement reussi");
            exit;
        } catch (PDOException $erreur) {
            // Affichage de l'erreur en cas de problème avec la base de données
            echo "Une erreur est survenue : " . $erreur->getMessage();
        }
    } else {
        // Si l'email est déjà utilisé, redirection avec un message d'erreur
        echo "Le mail choisi est déjà utilisé, veuillez en sélectionner un autre.";
        header("location:add_users_form.php?status=erreur&message=Le mail est déjà choisi");
    }
} else {
    // Redirection si tous les champs ne sont pas remplis
    header("location:add_users_form.php?status=erreur&message=Veuillez remplir tous les champs");
}
