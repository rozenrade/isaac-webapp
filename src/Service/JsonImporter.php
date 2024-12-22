<?php

// src/Service/JsonImporter.php
namespace App\Service;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class JsonImporter
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importJsonData(string $jsonFilePath)
    {
        // Vérifier si le fichier existe
        if (!file_exists($jsonFilePath)) {
            throw new FileException("Le fichier JSON n'existe pas.");
        }

        // Charger le contenu du fichier JSON
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        if ($data === null) {
            throw new \Exception("Erreur lors du décodage du JSON.");
        }

        // Pour chaque élément dans le JSON
        foreach ($data as $itemData) {
            // Créer une nouvelle instance de l'entité Item
            $item = new Item();
            $item->setName($itemData['name']);
            $item->setFilename($itemData['filename']);

            // Persister l'entité dans la base de données
            $this->entityManager->persist($item);
        }

        // Effectuer la sauvegarde en base de données
        $this->entityManager->flush();
    }
}
