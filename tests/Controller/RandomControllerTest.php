<?php

namespace App\Tests\Controller;

error_reporting(E_ALL);
ini_set('display_errors', '1');

use App\Entity\Boss;
use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class RandomControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    public function testBasic(): void
    {
        $this->assertTrue(true, 'PHPUnit fonctionne correctement.');
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        if (!$this->client) {
            throw new \Exception("Le client Symfony n'a pas pu être créé.");
        }
        
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    
        // Création du dossier des images si nécessaire
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $imagesDir = $projectDir . '/public/images/items/';
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0777, true);
        }
    
        // Créer des fichiers vides pour les items de test
        for ($i = 1; $i <= 12; $i++) {
            $filepath = $imagesDir . "item_$i.png";
            if (!file_exists($filepath)) {
                touch($filepath);
            }
        }
    
        // Nettoyer les tables concernées (Items, Boss, Character)
        $this->entityManager->createQuery('DELETE FROM App\Entity\Item')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Boss')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Character')->execute();
    
        // Création de 12 Items
        for ($i = 1; $i <= 12; $i++) {
            $item = new \App\Entity\Item();
            $item->setName("Item Test $i");
            $item->setFilename("public/images/items/item_$i.png");
            $this->entityManager->persist($item);
        }
    
        // Création d'un Boss
        $boss = new Boss();
        $boss->setName('Boss Test');
        $boss->setFilename('public/images/destinations/example.png');
        $this->entityManager->persist($boss);
    
        // Création d'un Character
        $character = new Character();
        $character->setName('Character Test');
        $character->setFilename('public/images/characters/example.png');
        $this->entityManager->persist($character);
    
        // Enregistrement des entités en base
        $this->entityManager->flush();
    }
    
    
    // Test de l'affichage de la page d'index.
    // On ajoute le paramètre "status=random" pour que le contrôleur fournisse la variable "statusURL".
    public function testIndexPageLoadsSuccessfully(): void
    {
        $this->client->request('GET', '/random?status=random');
    
        $this->assertResponseIsSuccessful();
    }
    
    // Test de la génération d'un build aléatoire
    public function testRandomBuildIsGenerated(): void
    {
        $this->client->request('GET', '/random?status=random');
    
        $session = $this->client->getRequest()->getSession();
        $currentBuild = $session->get('currentBuild');
    
        $this->assertNotNull($currentBuild, 'Un build devrait être généré et stocké en session.');
        $this->assertArrayHasKey('items', $currentBuild, 'Le build devrait contenir une clé "items".');
        $this->assertCount(12, $currentBuild['items'], 'Le build doit contenir 12 items.');
    }
}
