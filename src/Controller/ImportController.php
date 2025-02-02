<?php

// src/Controller/ImportController.php
namespace App\Controller;

use App\Service\JsonImporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[Route('/import', name: 'import')]
    public function import(JsonImporter $jsonImporter): Response
    {
        // Récupérer le chemin absolu du projet
        $projectDir = $this->getParameter('kernel.project_dir');
        // Chemin vers le fichier JSON généré (ici, à la racine du projet)
        $jsonFilePath = $projectDir . '/items.json';

        try {
            // Appeler le service pour importer les données depuis le fichier JSON
            $jsonImporter->importJsonData($jsonFilePath);

            // Réponse indiquant que l'importation a réussi
            return new Response('Données importées avec succès !');
        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return new Response('Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }
}
