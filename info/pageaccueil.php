<!DOCTYPE html> 
<html> 
<head>
  <title>accueil wikipink</title>
  <meta name="keywords" content="beginning html,learning">
  <meta name="description" content="A beginning web page for Web programming class">
  
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
    body {
        background-image: url('images.jpeg'); /* Replace with your image URL */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    .blanche {
        overflow: hidden; /* Clear fix pour étendre autour du contenu flottant */
        background-color: rgba(255, 255, 255, 0.8);  
        padding: 10px;
    }

    select.l3 {
        width: 250px; 
        height: 50px; 
        border: none;
        background-color: transparent;
        font-size: 16px; /* Définissez la taille de police souhaitée */
        transition: all 0.3s ease; /* Ajoute une transition fluide */
    }

    /* Style pour le survol */
    select.l3:hover {
        width: 300px; /* Définissez la largeur souhaitée lors du survol */
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

    li > a {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .l3 {
        border-top-color: white;
        border-bottom-color: white;
    }
    
    input[type=text] {
        height: 50px;
    }

    .container {
        text-align: center; /* Centrer horizontalement */
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

    .texte {
        background-color: rgba(255, 192, 203, 0.7); /* Rose avec 70% d'opacité */
        padding: 20px;
        border-radius: 10px; /* Optionnel : pour des coins arrondis */
        text-align: center; /* Centrer le texte */
        max-width: 600px; /* Limite la largeur du conteneur */
        margin: 20px auto; /* Centre le conteneur horizontalement */
        border: 2px solid rgba(0, 0, 0);
    }

    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
    }

    .conseil-container {
        width: 100%;
        overflow-x: auto; /* Défilement horizontal */
        white-space: nowrap; /* Empêcher le passage à la ligne des éléments */
    }

    .conseil {
        display: inline-block;
        width: 40%; /* Ajustez la largeur des conseils selon vos besoins */
        margin: 1%; /* Espacement entre les conseils */
        background-color: rgba(255, 255, 255, 0.9);
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        vertical-align: top;
    }

    .conseil img, .conseil video {
        width: 100%;
        height: auto;
    }

  /* Ajoutez cette règle pour réduire la taille de l'image du conseil */
  .conseil-img {
    max-width: 200px; /* Définissez la largeur maximale souhaitée */
    height: auto;
    display: block;
  }

  .conseil-vid{
    max-width: 200px; /* Définissez la largeur maximale souhaitée */
    height: auto;
    display: block;
  }

    .conseil-info h3 {
        margin-top: 0;
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

<div class="texte">
    <h1>Bienvenue sur wiki pink, le site de conseil le plus fiable d'internet.</h1>
    <b>Qu'allez-vous découvrir sur wiki pink aujourd'hui ?</b>
    <form action="recherche.php" method="GET">
        <input type="text" class="search-bar" placeholder="Rechercher..." id="search" name="search">
        <input type="submit" value="Rechercher" style="height: 50px; width: 100px;">
    </form>
</div>
<div class="div2">
    <h2>Nos meilleurs conseils :</h2>
</div>
<div class="div3 conseil-container">
<?php
$directory = 'conseils/';
$files = array_diff(scandir($directory),
array('..', '.'));

$conseils = [];
foreach ($files as $file) {
    $file_path = $directory . $file;
    $content = file_get_contents($file_path);
    $content_arr = json_decode($content, true);
    $comment_file = 'commentaires/' . pathinfo($file, PATHINFO_FILENAME) . '.txt' . '_notes.txt';
    if (file_exists($comment_file)) {
        $notes = file($comment_file, FILE_IGNORE_NEW_LINES);
        $total_notes = count($notes);
        $sum_notes = array_sum($notes);
        $average_note = $total_notes > 0 ? $sum_notes / $total_notes : 0;
    } else {
        $average_note = 0;
    }
    $content_arr['average_note'] = $average_note;
    $content_arr['file'] = $file;
    $conseils[] = $content_arr;
}

// Trier les conseils par note moyenne décroissante
usort($conseils, function ($a, $b) {
    return $b['average_note'] <=> $a['average_note'];
});

// Limiter aux 5 meilleurs conseils
$top_conseils = array_slice($conseils, 0, 5);

// Afficher les conseils
foreach ($top_conseils as $index => $conseil) {
    echo '<div class="conseil">';
    // Vérifie si le conseil a un média associé
    if (isset($conseil['media']) && !empty($conseil['media'])) {
        // Afficher le premier média associé (supposons que chaque conseil a un seul média)
        $media = $conseil['media'][0];
        $media_type = $media['type'];
        $media_path = $media['path'];
        if ($media_type === 'image') {
            echo '<img src="' . htmlspecialchars($media_path) . '" alt="Media" class="conseil-img">';
        } elseif ($media_type === 'video') {
            echo '<video controls>';
            echo '<source src="' . htmlspecialchars($media_path) . '" type="video/mp4" class="conseil-vid>';
            echo '</video>';
        }
    }
    echo '<div class="conseil-info">';
    echo '<h3>' . htmlspecialchars($conseil['titre']) . '</h3>';
    // Afficher le début du texte du conseil
    $debut_conseil = substr($conseil['texte'], 0, 50); // Par exemple, afficher les 50 premiers caractères
    echo "<p>" . htmlspecialchars($debut_conseil) . "...</p>"; // Ajoutez "..." pour indiquer que le texte continue
    echo '<a href="conseil.php?file=' . $conseil['file'] . '">Lire plus</a>';
    echo ' | <a href="modifier_conseil.php?file=' . $conseil['file'] . '">Modifier</a>';
    echo '</div>'; 
    echo '</div>'; 
}
?>
</div>
</body>
</html>        
