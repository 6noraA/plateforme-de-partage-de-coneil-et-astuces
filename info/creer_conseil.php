<?php
session_start();

// Assurez-vous que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer un Conseil</title>
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
  <h1>Créer un Conseil</h1>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $titre = $_POST['titre'];
      $texte = $_POST['texte'];
      $media_files = $_FILES['media'];

      // S'assurer que le dossier des conseils existe
      if (!is_dir('conseils')) {
          mkdir('conseils', 0755, true);
      }
      // S'assurer que le dossier des médias existe
      if (!is_dir('media')) {
          mkdir('media', 0755, true);
      }

      // Inclure l'ID de l'utilisateur dans les données du conseil
      $conseil = ['user_id' => $user_id, 'titre' => $titre, 'texte' => $texte, 'media' => []];

      // Gérer les téléchargements de fichiers
      if ($media_files['error'][0] === UPLOAD_ERR_OK) {
          for ($i = 0; $i < count($media_files['name']); $i++) {
              $file_name = basename($media_files['name'][$i]);
              $file_path = 'media/' . $file_name;
              $file_type = mime_content_type($media_files['tmp_name'][$i]);

              if (move_uploaded_file($media_files['tmp_name'][$i], $file_path)) {
                  $media_type = explode('/', $file_type)[0]; // image ou video
                  $conseil['media'][] = ['path' => $file_path, 'type' => $media_type];

                  // Réduire la taille des images
                  if ($media_type === 'image') {
                      $max_width = 800; // Largeur maximale
                      $max_height = 800; // Hauteur maximale

                      list($width, $height) = getimagesize($file_path);

                      if ($width > $max_width || $height > $max_height) {
                          $ratio = min($max_width / $width, $max_height / $height);
                          $new_width = $width * $ratio;
                          $new_height = $height * $ratio;

                          $src_image = null;
                          if ($file_type == 'image/jpeg') {
                              $src_image = imagecreatefromjpeg($file_path);
                          } elseif ($file_type == 'image/png') {
                              $src_image = imagecreatefrompng($file_path);
                          }

                          if ($src_image) {
                              $dst_image = imagecreatetruecolor($new_width, $new_height);
                              imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                              if ($file_type == 'image/jpeg') {
                                  imagejpeg($dst_image, $file_path, 90); // Qualité de 90
                              } elseif ($file_type == 'image/png') {
                                  imagepng($dst_image, $file_path, 9); // Compression maximale
                              }
                              imagedestroy($src_image);
                              imagedestroy($dst_image);
                          }
                      }
                  }
              }
          }
      }

      // Enregistrer le conseil
      $file_name = 'conseil_' . time() . '.json';
      file_put_contents('conseils/' . $file_name, json_encode($conseil, JSON_PRETTY_PRINT));

      // Rediriger vers la liste des conseils
      header('Location: lesconseils.php');
      exit;
  }
  ?>

  <form method="post" enctype="multipart/form-data">
    <label for="titre">Titre :</label><br>
    <input type="text" id="titre" name="titre" required><br><br>
    <label for="texte">Texte :</label><br>
    <textarea id="editor" name="texte"></textarea><br><br>
    <label for="media">Ajouter des images ou des vidéos :</label><br>
    <input type="file" name="media[]" multiple><br><br>
    <input type="submit" value="Enregistrer">
  </form>
  <br><a href="lesconseils.php">Retour à la liste des conseils</a>
</body>
</html>
