<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Afficher le Conseil</title>
  <style>
  

  .conseil{
    display: inline-block;
    max-width: 600px;
    position: absolute;
    background-color:white;
    border: solid;
    align: center;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
  }
  .img{
    position: center;
    width: 600px;
    heigh: 400px;
  }

  .video{
    position: center;
    width: 600px;
    heigh: 400px;
  }
  </style>
</head>
<body>
  
  <?php
  // Enregistrement du commentaire
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

      // Rediriger pour éviter la re-soumission du formulaire
      header('Location: conseil.php?file=' . urlencode($file_name));
      exit;
  }

  // Enregistrement de la note
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_file']) && isset($_POST['note'])) {
      $comment_file = $_POST['comment_file'];
      $note = (int)$_POST['note'];

      $note_file = $comment_file . '_notes.txt';

      // Ajouter la note au fichier
      $file = fopen($note_file, 'a');
      fwrite($file, $note . "\n");
      fclose($file);

      // Rediriger pour éviter la re-soumission du formulaire
      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit;
  }

  if (isset($_GET['file'])) {
      $file_name = $_GET['file'];
      $file_path = 'conseils/' . $file_name;
      
      if (file_exists($file_path)) {
          // Lire le contenu du fichier
          $content = file_get_contents($file_path);
          $content_arr = json_decode($content, true);
            
          echo '<div class="conseil">';
             // Afficher les médias associés
             if (isset($content_arr['media']) && is_array($content_arr['media']) && !empty($content_arr['media'])) {
                echo '<h3>Médias associés</h3>';
                foreach ($content_arr['media'] as $media) {
                    $media_path = htmlspecialchars($media['path']);
                    $media_type = $media['type'];

                    if ($media_type === 'image') {
                        if (file_exists($media_path)) {
                            echo "<img src='" . $media_path . "' alt='Image' class='img'><br>";
                        } else {
                            echo "<p>Impossible de charger l'image : " . $media_path . " (fichier non trouvé).</p>";
                        }
                    } elseif ($media_type === 'video') {
                        if (file_exists($media_path)) {
                            echo "<video controls width='200' class='video'>
                                    <source src='" . $media_path . "' type='video/mp4'>
                                  </video><br>";
                        } else {
                            echo "<p>Impossible de charger la vidéo : " . $media_path . " (fichier non trouvé).</p>";
                        }
                    } else {
                        echo "<p>Type de média inconnu : " . $media_type . "</p>";
                    }
                }
            } else {
                echo '<p>Aucun média associé.</p>';
            }



          if (json_last_error() === JSON_ERROR_NONE) {
              // Afficher le texte du conseil
              echo '<h2>' . htmlspecialchars($content_arr['titre']) . '</h2>';
              echo '<div>' . $content_arr['texte'] . '</div>';
            echo'</div>';
             
              // Afficher les commentaires
              $comment_file = 'commentaires/' . pathinfo($file_name, PATHINFO_FILENAME) . '.txt';
              echo '<h3>Commentaires</h3>';
              if (file_exists($comment_file)) {
                  $comments = file($comment_file, FILE_IGNORE_NEW_LINES);
                  foreach ($comments as $index => $comment) {
                      echo '<div>' . htmlspecialchars($comment) . '</div>';

                      // Afficher les notes
                      $note_file = $comment_file . '_notes.txt';
                      $notes = file_exists($note_file) ? file($note_file, FILE_IGNORE_NEW_LINES) : [];
                      $total_notes = count($notes);
                      $sum_notes = array_sum($notes);
                      $average_note = $total_notes > 0 ? $sum_notes / $total_notes : 0;
                      echo '<p>Note moyenne : ' . number_format($average_note, 2) . ' (' . $total_notes . ' votes)</p>';

                      // Formulaire pour ajouter une note
                      echo '<form method="post">
                              <input type="hidden" name="comment_file" value="' . htmlspecialchars($comment_file) . '">
                              <label for="note">Votre note :</label>
                              <select name="note" id="note" required>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                              </select>
                              <input type="submit" value="Noter">
                            </form>';
                  }
              } else {
                  echo '<p>Aucun commentaire pour l\'instant.</p>';
              }

              // Formulaire pour ajouter un commentaire
              echo '<h3>Ajouter un commentaire</h3>';
              echo '<form method="post">
                      <input type="hidden" name="file" value="' . htmlspecialchars($file_name) . '">
                      <label for="commentaire">Commentaire :</label><br>
                      <textarea id="commentaire" name="commentaire" required></textarea><br><br>
                      <input type="submit" value="Envoyer">
                    </form>';
          } else {
              echo "Erreur de décodage JSON : " . json_last_error_msg();
          }
      } else {
          echo "Le fichier spécifié n'existe pas.";
      }
  } else {
      echo "Aucun fichier spécifié.";
  }
  ?>
  <br><a href="lesconseils.php">Retour à la liste des conseils</a>
</body>
</html>
