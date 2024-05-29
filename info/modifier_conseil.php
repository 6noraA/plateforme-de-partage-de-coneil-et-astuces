<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier le Conseil</title>
  <script src="https://cdn.tiny.cloud/1/f16wvlkta7e1xsp6s9yef8jyad97jxo2t79fb4zcpiet62h3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: '#editor',
      plugins: 'image code media',
      toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
      height: 400,
    });
  </script>
  <style>
    textarea {
        width: 100%;
        height: 400px;
    }
  </style>
</head>
<body>
  <h1>Modifier le Conseil</h1>
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  if (isset($_GET['file'])) {
      $file_name = $_GET['file'];
      $file_path = 'conseils/' . $file_name;

      if (file_exists($file_path)) {
          $content = file_get_contents($file_path);
          $content_arr = json_decode($content, true);
      } else {
          echo "Le fichier spécifié n'existe pas.";
          exit;
      }
  } else {
      echo "Aucun fichier spécifié.";
      exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file']) && isset($_POST['titre']) && isset($_POST['texte'])) {
      $json_file_name = $_POST['file'];
      $titre = $_POST['titre'];
      $texte = $_POST['texte'];
      $media_files = $_FILES['media'];

      // Lire le contenu existant
      $content_arr = json_decode(file_get_contents('conseils/' . $json_file_name), true);
      $content_arr['titre'] = $titre;
      $content_arr['texte'] = $texte;

      // Gérer les téléchargements de fichiers
      if ($media_files['error'][0] === UPLOAD_ERR_OK) {
          for ($i = 0; $i < count($media_files['name']); $i++) {
              $uploaded_file_name = basename($media_files['name'][$i]);
              $file_path = 'media/' . $uploaded_file_name;
              $file_type = mime_content_type($media_files['tmp_name'][$i]);

              if (move_uploaded_file($media_files['tmp_name'][$i], $file_path)) {
                  $media_type = explode('/', $file_type)[0]; // image ou video
                  $content_arr['media'][] = ['path' => $file_path, 'type' => $media_type];
              }
          }
      }

      // Enregistrer les modifications du conseil
      file_put_contents('conseils/' . $json_file_name, json_encode($content_arr, JSON_PRETTY_PRINT));

      // Rediriger vers la liste des conseils
      header('Location: lesconseils.php');
      exit;
  }
  ?>

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="file" value="<?php echo htmlspecialchars($file_name); ?>">
    <label for="titre">Titre :</label><br>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($content_arr['titre']); ?>" required><br><br>
    <label for="texte">Texte :</label><br>
    <textarea id="editor" name="texte"><?php echo htmlspecialchars($content_arr['texte']); ?></textarea><br><br>
    <label for="media">Ajouter des images ou des vidéos :</label><br>
    <input type="file" name="media[]" multiple><br><br>
    <input type="submit" value="Enregistrer">
  </form>

  <h3>Médias existants</h3>
  <?php
  if (isset($content_arr['media'])) {
      foreach ($content_arr['media'] as $index => $media) {
          $file_path = $media['path'];
          if ($media['type'] === 'image') {
              echo "<div><img src='" . htmlspecialchars($file_path) . "' alt='Image' width='200'>";
          } elseif ($media['type'] === 'video') {
              echo "<div><video controls width='200'>
                      <source src='" . htmlspecialchars($file_path) . "' type='video/mp4'>
                    </video>";
              echo "<form method='post' action='supprimer_media.php' style='display:inline;'>
                      <input type='hidden' name='file' value='" . htmlspecialchars($file_name) . "'>
                      <input type='hidden' name='index' value='" . $index . "'>
                      <button type='submit' name='delete' value='Supprimer'>Supprimer</button>
                    </form>";
              echo "</div><br>";
          }
      }
  }
  ?>
  <br><a href="lesconseils.php">Retour à la liste des conseils</a>
</body>
</html>
