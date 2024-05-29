<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Conseils</title>


  <script>
        function navigateToPage(selectElement) {
            var url = selectElement.value;
            if (url) {
                window.location.href = url;
            }
        }
        window.onload = function() {
            var selectElement = document.querySelector('select.l3');
            selectElement.value = "default"; // Sélectionne "Liste d'outil" par défaut
        };

    </script>
<style>
.blanche {
        overflow: hidden; /* Clear fix pour étendre autour du contenu flottant */
        background-color: rgba(255, 255, 255, 0.8);  
        padding: 10px;
        
    }
    body {
        background-image: url('fonctionnement-conseil-administration-sa.jpg');
        
      }

    select.l3 {
   
    width: 250px; 

    
    height: 50px; 

   
    border: none;

   
    background-color: transparent;

    /* Augmenter la taille du texte à l'intérieur */
    font-size: 16px; /* Définissez la taille de police souhaitée */

    /* Style pour le survol */
    transition: all 0.3s ease; /* Ajoute une transition fluide */
}

/* Style pour le survol */
select.l3:hover {
    /* Augmenter la largeur du cadre lors du survol */
    width: 300px; /* Définissez la largeur souhaitée lors du survol */

    /* Rendre la couleur de fond visible lors du survol */
    background-color: rgba(255, 255, 255, 0.9); /* Définissez la couleur de fond souhaitée lors du survol */
}

    .blanche img {
        float: left; 
        margin-right: 10px;
    }
    .blanche nav {
        float: right; /* Aligner les liens à droite */
    }
    
    nav a {
        color: #000; 
        text-decoration: none; 
        margin-right: 10px; 
    }
    nav a:hover {
        text-decoration: underline; 
    }

    
    li>a{color:rgba(255, 255, 255, 0.8)}
    
    .l3 {border-top-color:white;
        border-bottom-color:white;
        }
    

    input[type=text] {
    height: 50px;
}

.container {
            text-align: center; /* Centrer horizontalement */
             /* Garder la même hauteur */
        }
.search-bar {
            width: 300px; 
            border: none; 
            border-bottom: 2px solid rgba(0, 0, 0); 
            padding: 5px; 
            box-sizing: border-box;
            font-size: 16px; 
            outline: none;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .search-bar::placeholder {
    color: rgba(0, 0, 0, 0.8); /* Couleur du placeholder noir avec 50% d'opacité */
}
.pink-link {
            color: black;
        }
</style>
</head>
<body>
<div class="blanche">
    <header>
        <a href="pageaccueil.php"><img src="wikipink.png" width="200" height="50" alt="Logo"></a>
        <nav>
            <div class="dropdown">
            <select name="country" class="l3" onchange="if(this.value) window.location.href=this.value;">
    <option value="default" selected>Liste d'outil</option>
    <option value="lesconseils.php">Liste de conseil</option>
    <option value="creer_conseil.php">Créer conseil</option>
    <option value="pageutilisateur.php">Mon compte</option>
</select>

            </div>
        </nav>
        <div class="container">
        <form action="recherche.php" method="GET">
            <input type="text" class="search-bar" placeholder="Rechercher..." id="search" name="search">
            <input type="submit" value="Rechercher" style="height: 50px; width: 100px;">
        </form>
</div>
    </header>
</div>
  <h1>Liste des Conseils</h1>
  <a href="creer_conseil.php">Créer un Nouveau Conseil</a>
  <ul>
  <?php
  $directory = 'conseils/';
  $files = array_diff(scandir($directory), array('..', '.'));

  if (count($files) > 0) {
      foreach ($files as $file) {
          $file_path = $directory . $file;
          $content = file_get_contents($file_path);
          $content_arr = json_decode($content, true);
          echo '<li>';
          echo '<a href="conseil.php?file=' . $file . '"  class="pink-link">' . htmlspecialchars($content_arr['titre']) . '</a>';
          echo ' | <a href="modifier_conseil.php?file=' . $file . '"  class="pink-link">Modifier</a>';
          echo '</li>';
      }
  } else {
      echo '<li>Aucun conseil disponible</li>';
  }
  ?>
  </ul>
</body>
</html>
