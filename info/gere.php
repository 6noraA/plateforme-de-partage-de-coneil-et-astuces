<?php
if ($_FILES['upload']) {
    $file = $_FILES['upload'];
    $upload_dir = 'uploads/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'];

    // Vérifiez si le type de fichier est autorisé
    if (in_array($file['type'], $allowed_types)) {
        $file_name = basename($file['name']);
        $target_file = $upload_dir . $file_name;

        // Déplacez le fichier téléchargé vers le répertoire de destination
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Retourner l'URL du fichier téléchargé
            echo json_encode(['url' => $target_file]);
        } else {
            echo json_encode(['error' => 'Échec du téléchargement du fichier.']);
        }
    } else {
        echo json_encode(['error' => 'Type de fichier non autorisé.']);
    }
} else {
    echo json_encode(['error' => 'Aucun fichier téléchargé.']);
}
?>
