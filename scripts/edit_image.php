<?php
// Chemin vers le dossier contenant les images (en remontant d'un niveau depuis "scripts")
$dossier = __DIR__ . '/../public/images/items';

// Vérifier que le dossier existe
if (!is_dir($dossier)) {
    die("Le dossier spécifié ($dossier) n'existe pas.\n");
}

// Liste des extensions d'images autorisées
$extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

// Tableau qui contiendra les informations des items
$items = [];

// Récupérer la liste des fichiers dans le dossier
$files = scandir($dossier);

foreach ($files as $file) {
    $cheminComplet = $dossier . DIRECTORY_SEPARATOR . $file;
    
    // Vérifier qu'il s'agit d'un fichier
    if (is_file($cheminComplet)) {
        // Récupérer l'extension en minuscules
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        // Si le fichier est une image autorisée
        if (in_array($extension, $extensionsAutorisees)) {
            // Supprimer certains caractères spéciaux : ! , : ; ? * < > | / \
            $nouveauNom = preg_replace('/[!,:;\?\*<>\|\/\\\\]+/', '', $file);
            
            // Remplacer les espaces par des underscores
            $nouveauNom = str_replace(' ', '_', $nouveauNom);
            
            // Ajouter le préfixe "img_" si ce n'est pas déjà présent
            if (strpos($nouveauNom, 'img_') !== 0) {
                $nouveauNom = 'img_' . $nouveauNom;
            }
            
            // Convertir en minuscules
            $nouveauNom = strtolower($nouveauNom);

            // Renommage si le nom a changé
            if ($nouveauNom !== $file) {
                $nouveauChemin = $dossier . DIRECTORY_SEPARATOR . $nouveauNom;
                
                // Vérifier qu'il n'existe pas déjà un fichier avec ce nom
                if (file_exists($nouveauChemin)) {
                    echo "Le fichier '$nouveauNom' existe déjà. Renommage de '$file' annulé.\n";
                    continue;
                } else {
                    if (rename($cheminComplet, $nouveauChemin)) {
                        echo "Fichier '$file' renommé en '$nouveauNom'.\n";
                        $file = $nouveauNom; // Utiliser le nouveau nom pour la suite
                    } else {
                        echo "Erreur lors du renommage de '$file'.\n";
                        continue;
                    }
                }
            } else {
                echo "Le fichier '$file' ne nécessite pas de modification.\n";
            }

            // Modifier le 'name' en remplaçant les underscores par des espaces
            $nameWithSpaces = str_replace('_', ' ', pathinfo($file, PATHINFO_FILENAME));
            
            // Récupérer seulement le nom du fichier pour le 'filepath' (sans le chemin complet)
            $fileNameOnly = pathinfo($file, PATHINFO_BASENAME);
            
            // Ajouter les informations de l'item dans le tableau
            $items[] = [
                'name'     => $nameWithSpaces,  // Utiliser le nouveau nom avec espaces
                'filepath' => '/' . $fileNameOnly,  // Le chemin du fichier dans le dossier public
            ];
        }
    }
}

// Débogage : afficher le contenu du tableau $items
var_dump($items);

// Encoder le tableau en JSON
$jsonData = json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if ($jsonData === false) {
    die("Erreur d'encodage JSON : " . json_last_error_msg());
}

// Créer le fichier JSON (par exemple, à la racine du projet)
$fichierJSON = __DIR__ . '/../items.json';

if (file_put_contents($fichierJSON, $jsonData) !== false) {
    echo "Le fichier JSON a été créé avec succès : $fichierJSON\n";
} else {
    echo "Erreur lors de la création du fichier JSON.\n";
}
``

?>
