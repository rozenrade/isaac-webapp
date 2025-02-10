<?php

$dossier = __DIR__ . '/../public/images/items';

if (!is_dir($dossier)) {
    die("Le dossier spécifié ($dossier) n'existe pas.\n");
}

$extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
$items = [];
$files = scandir($dossier);

foreach ($files as $file) {
    $cheminComplet = $dossier . DIRECTORY_SEPARATOR . $file;
    
    if (is_file($cheminComplet)) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        if (in_array($extension, $extensionsAutorisees)) {
            $nouveauNom = preg_replace('/[!,:;\?\*<>\|\/\\]+/', '', $file);
            $nouveauNom = str_replace(' ', '_', $nouveauNom);
            
            if (strpos($nouveauNom, 'img_') !== 0) {
                $nouveauNom = 'img_' . $nouveauNom;
            }
            
            $nouveauNom = strtolower($nouveauNom);

            if ($nouveauNom !== $file) {
                $nouveauChemin = $dossier . DIRECTORY_SEPARATOR . $nouveauNom;
                
                if (file_exists($nouveauChemin)) {
                    echo "Le fichier '$nouveauNom' existe déjà. Renommage de '$file' annulé.\n";
                    continue;
                } else {
                    if (rename($cheminComplet, $nouveauChemin)) {
                        echo "Fichier '$file' renommé en '$nouveauNom'.\n";
                        $file = $nouveauNom;
                    } else {
                        echo "Erreur lors du renommage de '$file'.\n";
                        continue;
                    }
                }
            } else {
                echo "Le fichier '$file' ne nécessite pas de modification.\n";
            }

            $nameWithSpaces = str_replace('_', ' ', pathinfo($file, PATHINFO_FILENAME));
            $fileNameOnly = pathinfo($file, PATHINFO_BASENAME);
            
            $items[] = [
                'name'     => $nameWithSpaces,
                'filepath' => '/' . $fileNameOnly,
            ];
        }
    }
}

var_dump($items);

$jsonData = json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if ($jsonData === false) {
    die("Erreur d'encodage JSON : " . json_last_error_msg());
}

$fichierJSON = __DIR__ . '/../items.json';

if (file_put_contents($fichierJSON, $jsonData) !== false) {
    echo "Le fichier JSON a été créé avec succès : $fichierJSON\n";
} else {
    echo "Erreur lors de la création du fichier JSON.\n";
}

?>
