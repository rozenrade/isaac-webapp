<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Vérifie si l'utilisateur existe déjà et le supprime
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        if ($existingUser) {
            $this->entityManager->remove($existingUser);
            $this->entityManager->flush();
        }

        // Crée un utilisateur de test
        $user = new User();
        $user->setEmail('test@example.com')
            ->setUsername('testuser')
            ->setPassword('testpassword');  // Assure-toi de bien hasher le mot de passe si nécessaire

        // Sauvegarde l'utilisateur
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Se connecter avec l'utilisateur
        $this->client->loginUser($user);
    }

    public function testDisplaySynergies(): void
    {
        $this->client->request('GET', '/profile/my-synergies');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplayBuilds(): void
    {
        $this->client->request('GET', '/profile/my-builds');

        $this->assertResponseIsSuccessful();
    }

    public function testEditProfileWithInvalidPassword(): void
    {
        $crawler = $this->client->request('GET', '/profile/edit');

        $form = $crawler->selectButton('Enregistrer')->form([
            'user_profile[currentPassword]' => 'wrongpassword',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile/edit');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Le mot de passe est incorrect.');
    }

    public function testEditProfileWithValidPassword(): void
    {
        $crawler = $this->client->request('GET', '/profile/edit');

        $form = $crawler->selectButton('Enregistrer')->form([
            'user_profile[currentPassword]' => 'testpassword',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile/edit');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Votre profil a été mis à jour.');
    }

    public function testEditPasswordWithInvalidCurrentPassword(): void
    {
        $crawler = $this->client->request('GET', '/profile/password/edit');

        $form = $crawler->selectButton('Changer le mot de passe')->form([
            'change_password[currentPassword]' => 'wrongpassword',
            'change_password[newPassword]' => 'NewPass123!',
            'change_password[confirmPassword]' => 'NewPass123!',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile/password/edit');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Le mot de passe actuel est incorrect.');
    }

    public function testEditPasswordWithMismatchedNewPassword(): void
    {
        $crawler = $this->client->request('GET', '/profile/password/edit');

        $form = $crawler->selectButton('Changer le mot de passe')->form([
            'change_password[currentPassword]' => 'testpassword',
            'change_password[newPassword]' => 'NewPass123!',
            'change_password[confirmPassword]' => 'DifferentPass!',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile/password/edit');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Les deux mots de passe ne correspondent pas.');
    }

    public function testEditPasswordWithValidData(): void
    {
        $crawler = $this->client->request('GET', '/profile/password/edit');

        $form = $crawler->selectButton('Changer le mot de passe')->form([
            'change_password[currentPassword]' => 'testpassword',
            'change_password[newPassword]' => 'NewPass123!',
            'change_password[confirmPassword]' => 'NewPass123!',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile/edit');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
