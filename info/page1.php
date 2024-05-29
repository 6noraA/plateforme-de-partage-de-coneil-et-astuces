<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    $user_file = 'users.json';

    // Créer le fichier users.json s'il n'existe pas
    if (!file_exists($user_file)) {
        file_put_contents($user_file, json_encode([]));
    }

    $users = json_decode(file_get_contents($user_file), true);

    // Vérifier si l'utilisateur existe déjà
    foreach ($users as $user) {
        if ($user['email'] === $email || $user['identifiant'] === $identifiant) {
            header('Location: inscription.php?error=Utilisateur existe déjà');
            exit;
        }
    }

    // Ajouter le nouvel utilisateur
    $users[] = [
        'email' => $email,
        'identifiant' => $identifiant,
        'password' => password_hash($password, PASSWORD_DEFAULT) // Hachage du mot de passe
    ];

    file_put_contents($user_file, json_encode($users, JSON_PRETTY_PRINT));

    // Rediriger vers la page de connexion ou la page utilisateur
    header('Location: connexion.php?success=Inscription réussie, veuillez vous connecter.');
    exit;
}


?>
