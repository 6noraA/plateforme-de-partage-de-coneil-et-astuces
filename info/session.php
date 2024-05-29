<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    $user_file = 'users.json';
    $users = json_decode(file_get_contents($user_file), true);

    foreach ($users as $user) {
        if (($user['email'] === $identifiant || $user['identifiant'] === $identifiant) && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['identifiant'];
            header('Location: pageaccueil.php');
            exit;
        }
    }

    header('Location: connexion.php?error=Identifiant ou mot de passe incorrect');
    exit;
}
?>
