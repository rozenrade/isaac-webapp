<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\AdminSynergyController;
use App\Entity\Synergy;
use App\Entity\User;
use App\Repository\SynergyRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminSynergyControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        // Crée un client de test
        $client = static::createClient();
    
        // Crée un utilisateur via l'EntityManager pour obtenir un ID auto-incrémenté
        $user = $this->createUser();
        $entityManager = $client->getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class);
        $entityManager->persist($user);
        $entityManager->flush();  // Ceci enregistre l'utilisateur et lui attribue un ID auto-incrémenté
    
        // Authentifie l'utilisateur dans le client
        $client->loginUser($user);
    
        // Crée une synergie pour tester
        $synergy = new Synergy();
        $synergy->setName('Test Synergy');
        $entityManager->persist($synergy);
        $entityManager->flush();  // Ceci va générer un ID pour la synergie
    
        // Crée un mock du repository Synergy
        $mockRepo = $this->createMock(SynergyRepository::class);
        $mockRepo->expects($this->once())
            ->method('findAll')
            ->willReturn([$synergy]); // Retourne la synergie récemment créée
    
        // Injection du mock dans le container
        $client->getContainer()->set(SynergyRepository::class, $mockRepo);
    
        // Envoie une requête GET sur la route 'app_admin_synergy_index'
        $client->request('GET', '/admin/synergie');
    
        // Vérifie que la réponse est bien 200 et que la vue attendue est rendue
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1', 'Synergies');  // Vérifie que le titre existe dans la page
    
        // Vérifie que le lien vers la synergie existe et contient un ID valide
        $this->assertSelectorExists('a[href="/admin/synergie/' . $synergy->getId() . '"]');
    }
    
    private function createUser(): User
    {
        // Crée un utilisateur complet avec des informations nécessaires
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@example.com');
        $user->setPassword('password'); // Vous pouvez utiliser un encodeur de mot de passe ici si nécessaire
        $user->setRoles(['ROLE_ADMIN']);
    
        return $user;
    }
    
}
