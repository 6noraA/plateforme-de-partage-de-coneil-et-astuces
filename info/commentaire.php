<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file']) && isset($_POST['commentaire'])) {
  $file_name = $_POST['file'];
  $commentaire = $_POST['commentaire'];

  $comment_file = 'commentaires/' . pathinfo($file_name, PATHINFO_FILENAME) . '.txt';

  // S'assurer que le dossier des commentaires existe
  if (!is_dir('commentaires')) {
    mkdir('commentaires', 0755, true);
  }

  // Ajouter le commentaire au fichier
  $file = fopen($comment_file, 'a');
  fwrite($file, $commentaire . "\n");
  fclose($file);

  // Rediriger vers la page du conseil
  header('Location: conseil.php?file=' . urlencode($file_name));
  exit;
} else {
  echo 'Invalid request.';
}
?>
