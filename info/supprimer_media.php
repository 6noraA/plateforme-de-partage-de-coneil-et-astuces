<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file']) && isset($_POST['index'])) {
    $file_name = $_POST['file'];
    $index = $_POST['index'];

    $file_path = 'conseils/' . $file_name;

    if (file_exists($file_path)) {
        // Charger le contenu du fichier JSON
        $content = file_get_contents($file_path);
        $content_arr = json_decode($content, true);

        // Vérifier si l'index est valide
        if (isset($content_arr['media'][$index])) {
            // Supprimer le média correspondant à l'index
            unset($content_arr['media'][$index]);

            // Réindexer le tableau pour éviter les clés manquantes
            $content_arr['media'] = array_values($content_arr['media']);

            // Enregistrer les modifications dans le fichier JSON
            file_put_contents($file_path, json_encode($content_arr, JSON_PRETTY_PRINT));

            // Rediriger vers la page de modification de conseil
            header('Location: modifier_conseil.php?file=' . urlencode($file_name));
            exit;
        } else {
            echo "L'index spécifié n'est pas valide.";
        }
    } else {
        echo "Le fichier spécifié n'existe pas.";
    }
} else {
    echo 'Invalid request.';
}
?>

