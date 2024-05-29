<?php
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Charger les informations de l'utilisateur depuis un fichier ou une base de données
// Pour cet exemple, on suppose que les informations de l'utilisateur sont stockées dans un fichier JSON
$user_data_file = 'users/' . $user_id . '.json';
$user_data = json_decode(file_get_contents($user_data_file), true);

// Traiter le formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Mettre à jour les informations de l'utilisateur
    $user_data['username'] = $new_username;
    $user_data['email'] = $new_email;
    $user_data['password'] = $new_password;

    // Enregistrer les nouvelles informations
    file_put_contents($user_data_file, json_encode($user_data, JSON_PRETTY_PRINT));

    // Rediriger vers la page de l'utilisateur après la mise à jour
    header('Location: utilisateur.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier le Compte</title>
  <style>
    body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }
    .container {
      width: 300px;
      margin: 50px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .container h1 {
      text-align: center;
    }
    .container form {
      display: flex;
      flex-direction: column;
    }
    .container form input {
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .container form input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Modifier le Compte</h1>
    <form method="post">
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>

      <label for="password">Nouveau Mot de Passe :</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Mettre à jour">
    </form>
  </div>
</body>
</html>

