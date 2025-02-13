<?php

namespace App\Controller;

use App\Service\JsonImporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[Route('/import-item', name: 'import_item')]
    public function importItem(JsonImporter $jsonImporter): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $jsonFilePath = $projectDir . '/items.json';

        try {
            $jsonImporter->importJsonDataItem($jsonFilePath);

            return new Response('Données importées avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'importation : ' . $e->getMessage());
        }

        return new Response('No items could be imported.');
    }

    #[Route('/import-boss', name: 'import_boss')]
    public function importBoss(JsonImporter $jsonImporter): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $jsonFilePath = $projectDir . '/boss_images.json';

        try {
            $jsonImporter->importJsonDataBoss($jsonFilePath);

            return new Response('Données importées avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'importation : ' . $e->getMessage());
        }

        return new Response('No items could be imported.');
    }

    #[Route('/import-characters', name: 'import_characters')]
    public function importCharacters(JsonImporter $jsonImporter): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $jsonFilePath = $projectDir . '/characters_images.json';

        try {
            $jsonImporter->importJsonDataCharacters($jsonFilePath);

            return new Response('Données importées avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'importation : ' . $e->getMessage());
        }

        return new Response('No items could be imported.');
    }
}

