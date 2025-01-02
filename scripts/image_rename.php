<?php

// Définir le chemin vers le dossier d'images dans le dossier public
$directory = __DIR__ . '/public/images/items';  // Chemin relatif à la racine du projet

// Ouvrir le dossier
if ($handle = opendir($directory)) {
    // Parcourir tous les fichiers du dossier
    while (false !== ($file = readdir($handle))) {
        // Ignorer les fichiers "." et ".."
        if ($file != "." && $file != "..") {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            // Vérifier si c'est un fichier
            if (is_file($filePath)) {
                // Supprimer les espaces, remplacer par des underscores et convertir en minuscules
                $newFileName = strtolower(str_replace(' ', '_', $file));
                $newFilePath = $directory . DIRECTORY_SEPARATOR . $newFileName;
                
                // Renommer le fichier
                rename($filePath, $newFilePath);
                echo "Renommé : $file -> $newFileName\n";
            }
        }
    }
    closedir($handle);
} else {
    echo "Impossible d'ouvrir le dossier $directory.\n";
}
?>
