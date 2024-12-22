<?php

// Chemin vers le fichier JSON
$filePath = __DIR__ . '/../assets/data/output.json'; // Ajustez le chemin selon votre structure

if (!file_exists($filePath)) {
    die("Fichier introuvable : $filePath\n");
}

// Chargement du contenu JSON
$jsonData = file_get_contents($filePath);

if (!$jsonData) {
    die("Le fichier JSON est invalide ou vide.\n");
}

// Décoder le JSON
$data = json_decode($jsonData, true);

if (!$data) {
    die("Le fichier JSON est invalide ou ne peut pas être parsé.\n");
}

// Extraction des champs "name" et ajout du champ "filename"
$cleanedData = [];
foreach ($data as $item) {
    if (isset($item['name'])) {
        // Générer le nom de fichier à partir du nom (par exemple, transformer en minuscules et remplacer les espaces par des underscores)
        $filename = strtolower(str_replace(' ', '_', $item['name'])) . '.png';
        
        // Ajouter l'élément avec "name" et "filename"
        $cleanedData[] = [
            'name' => $item['name'],
            'filename' => $filename
        ];
    }
}

// Sauvegarde du JSON nettoyé avec "name" et "filename"
$outputPath = __DIR__ . '/../assets/data/cleaned_items_with_filename.json';
file_put_contents($outputPath, json_encode($cleanedData, JSON_PRETTY_PRINT));

echo "JSON nettoyé et sauvegardé dans : $outputPath\n";
