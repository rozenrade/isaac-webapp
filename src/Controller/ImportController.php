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
        // // Chemin vers votre fichier JSON
        // $jsonFilePath = __DIR__ . '/../../public/assets/data/cleaned_items_with_filename.json';

        // try {
        //     // Appeler le service pour importer les données
        //     $jsonImporter->importJsonData($jsonFilePath);

        //     // Réponse indiquant que l'importation a réussi
        //     return new Response('Données importées avec succès !');
        // } catch (\Exception $e) {
        //     // Réponse en cas d'erreur
        //     return new Response('Erreur : ' . $e->getMessage());
        // }

        return new Response('Importation failed, no data could be retrieved.');
    }
}
