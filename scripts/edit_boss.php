<?php

// Dossier des Boss
$dossierBoss = __DIR__ . '/../public/images/destinations';

// Vérifie si le dossier existe
if (!is_dir($dossierBoss)) {
    die("Le dossier spécifié pour les Boss ($dossierBoss) n'existe pas.\n");
}

$extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
$bossImages = [];
$files = scandir($dossierBoss);

foreach ($files as $file) {
    $cheminComplet = $dossierBoss . DIRECTORY_SEPARATOR . $file;

    if (is_file($cheminComplet)) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // Vérifie si l'extension est autorisée
        if (in_array($extension, $extensionsAutorisees)) {
            // Ajoute simplement le fichier sans modifier son nom
            $fileNameOnly = pathinfo($file, PATHINFO_BASENAME);

            // Ajoute l'image au tableau
            $bossImages[] = [
                'name'     => pathinfo($file, PATHINFO_FILENAME),  // Utilise le nom sans l'extension
                'filepath' => '/images/boss/' . $fileNameOnly,  // Chemin d'accès à l'image
            ];
        }
    }
}

// Affiche le tableau des images pour vérifier
var_dump($bossImages);

// Convertit le tableau en JSON
$jsonData = json_encode($bossImages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if ($jsonData === false) {
    die("Erreur d'encodage JSON : " . json_last_error_msg());
}

// Crée le fichier JSON
$fichierJSON = __DIR__ . '/../boss_images.json';

if (file_put_contents($fichierJSON, $jsonData) !== false) {
    echo "Le fichier JSON a été créé avec succès : $fichierJSON\n";
} else {
    echo "Erreur lors de la création du fichier JSON.\n";
}
?>
