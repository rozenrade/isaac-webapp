<?php

namespace App\Tests\Controller;

use App\Controller\SynergiesController;
use App\Entity\User;
use App\Entity\Synergy;
use App\Repository\SynergyRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Test double pour SynergiesController.
 * On override getUser(), isGranted() et redirectToRoute() pour simplifier le test.
 */
class TestSynergiesController extends SynergiesController
{
    private $testUser;
    private $isGrantedValue = false;

    public function setTestUser(?User $user)
    {
        $this->testUser = $user;
    }

    public function setIsGrantedValue(bool $value)
    {
        $this->isGrantedValue = $value;
    }

    // On override getUser() pour retourner l'utilisateur de test.
    protected function getUser(): ?User
    {
        return $this->testUser;
    }

    // On override isGranted() pour retourner une valeur définie dans le test.
    protected function isGranted($attribute, $subject = null): bool
    {
        return $this->isGrantedValue;
    }

    // On override redirectToRoute() pour retourner une RedirectResponse dont l'URL cible sera le nom de la route.
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($route, $status, $headers);
    }
}

class SynergiesControllerTest extends TestCase
{
    /**
     * Test : l'utilisateur n'est pas authentifié, donc la méthode save() doit rediriger vers "app_login".
     */
    public function testSaveNotAuthenticated(): void
    {
        $controller = new TestSynergiesController();
        $controller->setIsGrantedValue(false); // Simule un utilisateur non authentifié

        // Création d'un mock du repository (aucun appel attendu ici)
        $mockRepo = $this->createMock(SynergyRepository::class);

        $response = $controller->save(1, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_login', $response->headers->get('Location'));  // Correction ici
    }

    /**
     * Test : utilisateur authentifié mais aucune synergie n'est trouvée (find() retourne null).
     * On s'attend à être redirigé vers "app_synergies".
     */
    public function testSaveSynergyNotFound(): void
    {
        $controller = new TestSynergiesController();
        $controller->setIsGrantedValue(true);
        // Crée un utilisateur de test
        $user = new User();
        $controller->setTestUser($user);

        $mockRepo = $this->createMock(SynergyRepository::class);
        $mockRepo->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $controller->save(1, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_synergies', $response->headers->get('Location'));  // Correction ici
    }

    /**
     * Test : utilisateur authentifié et synergie trouvée.
     * On s'attend à ce que la méthode addUtilisateur() soit appelée, puis save() sur le repo, et la redirection vers "app_synergies".
     */
    public function testSaveSynergySuccess(): void
    {
        $controller = new TestSynergiesController();
        $controller->setIsGrantedValue(true);
        $user = new User();
        $controller->setTestUser($user);

        // Création d'une synergie factice (ici on utilise un mock pour vérifier que addUtilisateur() est bien appelée)
        $synergy = $this->createMock(Synergy::class);
        $synergy->expects($this->once())
            ->method('addUtilisateur')
            ->with($user);

        $mockRepo = $this->createMock(SynergyRepository::class);
        $mockRepo->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($synergy);
        $mockRepo->expects($this->once())
            ->method('save')
            ->with($synergy);

        $response = $controller->save(1, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_synergies', $response->headers->get('Location'));  // Correction ici
    }

    /**
     * Test : pour la méthode removeSynergieFromUser, si l'utilisateur n'est pas authentifié, redirection vers "app_login".
     */
    public function testRemoveSynergyNotAuthenticated(): void
    {
        $controller = new TestSynergiesController();

        // Création d'un mock de Security qui retourne null pour getUser()
        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $mockRepo = $this->createMock(SynergyRepository::class);

        $response = $controller->removeSynergieFromUser(1, $mockSecurity, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_login', $response->headers->get('Location'));  // Correction ici
    }

    /**
     * Test : pour removeSynergieFromUser, si la synergie n'est pas trouvée, redirection vers "app_user_show_synergies".
     */
    public function testRemoveSynergyNotFound(): void
    {
        $controller = new TestSynergiesController();
        $user = new User();

        // Mock de Security qui retourne un utilisateur
        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $mockRepo = $this->createMock(SynergyRepository::class);
        $mockRepo->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $controller->removeSynergieFromUser(1, $mockSecurity, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_user_show_synergies', $response->headers->get('Location'));  // Correction ici
    }

    /**
     * Test : pour removeSynergieFromUser, en cas de succès.
     * On vérifie que removeSynergieFromUser() du repo est appelé et la redirection vers "app_synergies".
     */
    public function testRemoveSynergySuccess(): void
    {
        $controller = new TestSynergiesController();
        $user = new User();

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $synergy = $this->createMock(Synergy::class);
        $mockRepo = $this->createMock(SynergyRepository::class);
        $mockRepo->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($synergy);
        $mockRepo->expects($this->once())
            ->method('removeSynergieFromUser')
            ->with($synergy, $user);

        $response = $controller->removeSynergieFromUser(1, $mockSecurity, $mockRepo);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('app_synergies', $response->headers->get('Location'));  // Correction ici
    }
}
