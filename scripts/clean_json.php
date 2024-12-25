<?php

// Chemin vers le fichier JSON
$filePath = __DIR__ . '/../assets/data/items.json';  // Modifiez selon l'emplacement de votre fichier

// Vérifier si le fichier existe
if (!file_exists($filePath)) {
    die("Le fichier JSON est introuvable.\n");
}

// Charger le contenu du fichier JSON
$jsonData = file_get_contents($filePath);

// Vérifier si le contenu a bien été récupéré
if (!$jsonData) {
    die("Le fichier JSON est vide ou invalide.\n");
}

// Décoder le JSON en tableau PHP
$data = json_decode($jsonData, true);

// Vérifier si le décodage a échoué
if ($data === null) {
    die("Erreur de décodage du fichier JSON : " . json_last_error_msg() . "\n");
}

// Initialiser le tableau nettoyé
$cleanedData = [];

// Parcourir les données pour récupérer uniquement les champs "name" et "filename"
foreach ($data as $item) {
    if (isset($item['name'])) {
        // Créer le nom du fichier basé sur "name" (remplacer les espaces par des underscores et mettre en minuscules)
        $filename = strtolower(str_replace(' ', '_', $item['name'])) . '.png';

        // Ajouter le nouvel élément avec "name" et "filename"
        $cleanedData[] = [
            'name' => $item['name'],
            'filename' => $filename
        ];
    }
}

// Sauvegarder le tableau nettoyé dans un nouveau fichier JSON
$outputPath = __DIR__ . '/../assets/data/cleaned_items.json';  // Modifiez selon où vous souhaitez sauvegarder le fichier
file_put_contents($outputPath, json_encode($cleanedData, JSON_PRETTY_PRINT));

echo "Fichier JSON nettoyé et sauvegardé dans : $outputPath\n";
