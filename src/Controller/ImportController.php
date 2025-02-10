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
        // $projectDir = $this->getParameter('kernel.project_dir');
        // $jsonFilePath = $projectDir . '/items.json';

        // try {
        //     // $jsonImporter->importJsonData($jsonFilePath);

        //     // return new Response('Données importées avec succès !');
        // } catch (\Exception $e) {
            // return new Response('Erreur lors de l\'importation : ' . $e->getMessage());
        // }

        return new Response('No items could be imported.');
    }
}

