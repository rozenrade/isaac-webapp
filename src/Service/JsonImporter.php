<?php
// src/Service/JsonImporter.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item; // Remplacez par l'entité concernée

class JsonImporter
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Importer les données à partir d'un fichier JSON
     *
     * @param string $jsonFilePath Chemin complet vers le fichier JSON
     *
     * @throws \Exception En cas d'erreur d'importation
     */
    public function importJsonData(string $jsonFilePath): void
    {
        if (!file_exists($jsonFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas: $jsonFilePath");
        }

        $jsonContent = file_get_contents($jsonFilePath);
        $data = json_decode($jsonContent, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur lors de l'analyse du JSON: " . json_last_error_msg());
        }

        foreach ($data as $itemData) {
            $item = new Item();
            $item->setName($itemData['name']);
            $item->setFilename($itemData['filename']);
            // Si d'autres champs existent, les setter ici

            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();
    }
}
