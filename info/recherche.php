<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de la recherche</title>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];
    // Spécifiez le répertoire à parcourir
    $directory = 'conseils';

    // Vérifiez si le répertoire existe
    if (is_dir($directory)) {
        // Obtenez la liste des fichiers dans le répertoire, en excluant les entrées spéciales
        $files = array_diff(scandir($directory), array('..', '.'));

        // Variable pour stocker les fichiers contenant le mot recherché
        $matchingFiles = array();

        // Parcourir chaque fichier
        foreach ($files as $file) {
            $filePath = $directory . '/' . $file;

            // Vérifiez si c'est un fichier et non un répertoire
            if (is_file($filePath)) {
                // Lire le contenu du fichier
                $content = file_get_contents($filePath);
                $content_arr = json_decode($content, true);

                // Vérifiez si le titre ou le contenu du fichier contient le mot recherché
                if (stripos($content_arr['titre'], $search) !== false || stripos($content, $search) !== false) {
                    // Ajoutez le fichier à la liste des fichiers correspondants
                    $matchingFiles[] = $file;
                }
            }
        }

        // Affichez les fichiers contenant le mot recherché
        if (count($matchingFiles) > 0) {
            echo "<h2>Les fichiers suivants contiennent le mot ou la phrase '$search' :</h2><ul>";
            foreach ($matchingFiles as $matchingFile) {
                $content = file_get_contents($directory . '/' . $matchingFile);
                $content_arr = json_decode($content, true);
                echo '<li>';
                echo '<a href="conseil.php?file=' . $matchingFile . '">' . htmlspecialchars($content_arr['titre']) . '</a>';
                echo ' | <a href="modifier_conseil.php?file=' . $matchingFile . '">Modifier</a>';
                echo '</li>';
            }
            echo "</ul>";
        } else {
            echo "<p>Aucun fichier ne contient le mot ou la phrase '$search'.</p>";
        }
    } else {
        echo "<p>Le répertoire spécifié n'existe pas.</p>";
    }
} else {
    echo "<p>Aucun mot ou phrase de recherche n'a été spécifié.</p>";
}
?>

</body>
</html>
