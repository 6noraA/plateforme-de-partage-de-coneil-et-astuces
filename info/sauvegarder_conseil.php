<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $texte = $_POST['texte'];
    $file_name = isset($_POST['file']) ? $_POST['file'] : uniqid() . '.json';
    $file_path = 'conseils/' . $file_name;

    $media = [];

    // Gestion de l'upload des fichiers
    if (isset($_FILES['media'])) {
        foreach ($_FILES['media']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['media']['error'][$key] == UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['media']['tmp_name'][$key];
                $file_type = $_FILES['media']['type'][$key];
                $file_name_media = $_FILES['media']['name'][$key];

                $file_ext = pathinfo($file_name_media, PATHINFO_EXTENSION);
                $file_dest = 'uploads/' . uniqid() . '.' . $file_ext;

                // Vérifiez si le type de fichier est une image ou une vidéo
                if (strpos($file_type, 'image') !== false) {
                    $type = 'image';
                } elseif (strpos($file_type, 'video') !== false) {
                    $type = 'video';
                } else {
                    continue; // Ignorer les types de fichiers non pris en charge
                }

                // Créer le dossier des uploads s'il n'existe pas
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }

                if (move_uploaded_file($file_tmp, $file_dest)) {
                    $media[] = [
                        'type' => $type,
                        'path' => $file_dest
                    ];
                }
            }
        }
    }

    // Charger les médias existants s'il s'agit d'une modification
    if (file_exists($file_path)) {
        $existing_data = json_decode(file_get_contents($file_path), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $media = array_merge($existing_data['media'], $media);
        }
    }

    $data = [
        'titre' => $titre,
        'texte' => $texte,
        'media' => $media
    ];

    file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));

    header('Location: conseil.php?file=' . urlencode($file_name));
    exit;
}
?>
