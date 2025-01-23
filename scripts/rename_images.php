<?php

$directory = '../public/images/destinations/'; // Dossier des images

if (!is_dir($directory)) {
    die("Le dossier spécifié n'existe pas : $directory\n");
}

// Lire tous les fichiers du dossier
$files = scandir($directory);

foreach ($files as $file) {
    // Ignorer les dossiers spéciaux
    if ($file === '.' || $file === '..') {
        continue;
    }

    $oldPath = $directory . '/' . $file;

    // Vérifier si c'est bien un fichier et pas un dossier
    if (!is_file($oldPath)) {
        continue;
    }

    // Convertir en minuscule et remplacer espaces par des tirets
    $newFileName = strtolower(str_replace(' ', '-', $file));
    $newPath = $directory . '/' . $newFileName;

    // Vérifier si le fichier a déjà le bon nom
    if ($oldPath !== $newPath) {
        if (rename($oldPath, $newPath)) {
            echo "Renommé : $file → $newFileName\n";
        } else {
            echo "❌ Erreur : Impossible de renommer $file\n";
        }
    }
}

echo "✅ Tous les fichiers dans '$directory' ont été renommés avec succès !\n";
