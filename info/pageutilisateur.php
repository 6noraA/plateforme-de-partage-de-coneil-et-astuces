<?php
// Démarre la session
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

/**
 * Fonction pour compter le nombre de conseils créés par un utilisateur
 *
 * @param string $user_id L'ID de l'utilisateur
 * @return int Le nombre de conseils créés par l'utilisateur
 */
function countUserConseils($user_id) {
    $directory = 'conseils/';
    $conseilCount = 0;

    // Vérifie si le répertoire des conseils existe
    if (is_dir($directory)) {
        // Scanne le répertoire et obtient tous les fichiers sauf '.' et '..'
        $files = array_diff(scandir($directory), array('..', '.'));

        // Parcourt chaque fichier
        foreach ($files as $file) {
            $file_path = $directory . $file;
            // Vérifie si le fichier est lisible
            if (is_readable($file_path)) {
                $content = file_get_contents($file_path);
                $content_arr = json_decode($content, true);

                // Vérifie si le fichier JSON a été correctement décodé
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Vérifie si l'ID de l'utilisateur dans le fichier correspond à l'ID de l'utilisateur connecté
                    if (isset($content_arr['user_id']) && $content_arr['user_id'] === $user_id) {
                        $conseilCount++;
                    }
                }
            }
        }
    }
    
    return $conseilCount;
}

// Appelle la fonction pour compter les conseils de l'utilisateur
$conseilCount = countUserConseils($user_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Utilisateur</title>
  <style>
    .div1 {
      overflow: hidden;
      background-color: white;
      padding: 10px;
    }

    body {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: pink;
    }
  </style>
</head>
<body>
  <form action="page1.php" method="post"> 
    <div class="div1">
        <!-- Affiche le message de bienvenue avec l'ID de l'utilisateur -->
        <h1>Bienvenue <?php echo htmlspecialchars($user_id); ?></h1>
    </div>
    <div class="div2">
        <!-- Lien pour créer un nouveau conseil -->
        <h2><a href="creer_conseil.php">Souhaitez-vous poster un conseil?</a></h2>
    </div>
    <div class="div3">
        <h2>Mes conseils:</h2>
        <ul>
  <?php
  $directory = 'conseils/';
  $files = array_diff(scandir($directory), array('..', '.'));

  // Vérifie si des fichiers sont présents dans le répertoire
  if (count($files) > 0) {
      // Parcourt chaque fichier
      foreach ($files as $file) {
          $file_path = $directory . $file;
          $content = file_get_contents($file_path);
          $content_arr = json_decode($content, true);

          // Vérifie si l'ID de l'utilisateur dans le fichier correspond à l'ID de l'utilisateur connecté
          if ($content_arr['user_id'] === $user_id) {
              echo '<li>';
              echo '<a href="conseil.php?file=' . urlencode($file) . '">' . htmlspecialchars($content_arr['titre']) . '</a>';
              echo ' | <a href="modifier_conseil.php?file=' . urlencode($file) . '">Modifier</a>';
              echo '</li>';
          }
      }
  } else {
      // Affiche un message si aucun conseil n'est disponible
      echo '<li>Aucun conseil disponible</li>';
  }
  ?>
  </ul>
    </div>
    <div class="div4">
    <h2><a href="modifier_compte.php">Modifier mon compte</a></h2>
    </div>
    <div class="div5">
        <h2><a href="lesconseils.php">Autre conseil?</a></h2>
    </div>
    <div class="div6">
        <h2>Stat de contribution:</h2>
        <!-- Affiche le nombre de conseils créés par l'utilisateur -->
        <p>Nombre de conseils créés : <?php echo $conseilCount; ?></p>
    </div>
  </form>
</body>
</html>
