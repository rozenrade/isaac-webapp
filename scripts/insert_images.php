<?php

$directory = '../public/images/characters'; // Dossier des images

// Connexion à la base de données (⚠️ MODIFIE avec tes identifiants)
$pdo = new PDO('mysql:host=localhost;dbname=isaac_db;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Lire les fichiers du dossier
$files = scandir($directory);

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }

    $filePath = $directory . '/' . $file;

    // Vérifier si c'est bien un fichier
    if (!is_file($filePath)) {
        continue;
    }

    // Nettoyer le nom : enlever l'extension, remplacer les tirets par des espaces et mettre en majuscules
    $name = ucfirst(str_replace('-', ' ', pathinfo($file, PATHINFO_FILENAME)));

    // Vérifier si l'image existe déjà en BDD pour éviter les doublons
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `character` WHERE filename = :filename");
    $stmt->execute(['filename' => $file]);
    $exists = $stmt->fetchColumn();

    if (!$exists) {
        // Insérer en base de données
        $stmt = $pdo->prepare("INSERT INTO `character` (name, filename) VALUES (:name, :filename)");
        $stmt->execute([
            'name' => $name,
            'filename' => $file
        ]);

        echo "✅ Inséré : $name ($file)\n";
    } else {
        echo "⚠️ Déjà en BDD : $name ($file)\n";
    }
}

echo "🎉 Tous les fichiers ont été traités !\n";
