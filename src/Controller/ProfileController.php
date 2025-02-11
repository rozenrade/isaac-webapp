<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\UserProfileType;
use App\Repository\BuildRepository;
use App\Repository\SynergyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile/my-synergies', name: 'app_user_show_synergies')]
    public function displaySynergies(SynergyRepository $synergyRepository, Security $security): Response
    {
        $loggedUser = $security->getUser();
        $userSynergies = $synergyRepository->findSynergiesByUser($loggedUser);

        $formattedSynergies = [];
        foreach ($userSynergies as $synergie) {

            $itemData = [];
            foreach ($synergie->getItem() as $item) {
                // On s'assure que $item est un objet et qu'il possède les bonnes méthodes
                if (is_object($item) && method_exists($item, 'getName') && method_exists($item, 'getFilename')) {
                    $itemData[] = [
                        'name' => $item->getName(),
                        'filename' => $item->getFilename(),
                    ];
                }
            }

            $formattedSynergies[] = [
                'id' => $synergie->getId(),
                'name' => $synergie->getName(),
                'item' => $itemData,
                'utilisateurs' => $synergie->getUtilisateurs() 
            ];
        }

        return $this->render('profile/synergies.html.twig', [
            'synergies' => $formattedSynergies
        ]);
    }


    #[Route('/profile/my-builds', name: 'app_user_show_builds')]
    public function displayBuilds(BuildRepository $buildRepository, Security $security): Response
    {
        $loggedUser = $security->getUser();
        $userBuilds = $buildRepository->findBuildsByUser($loggedUser);

        $formattedBuilds = [];

        foreach ($userBuilds as $build) {
            $itemData = [];
            foreach ($build->getItem() as $item) {
                // Assurez-vous que $item est un objet et qu'il possède les méthodes getName() et getFilename()
                if (is_object($item) && method_exists($item, 'getName') && method_exists($item, 'getFilename')) {
                    $itemData[] = [
                        'name' => $item->getName(),
                        'filename' => $item->getFilename(),
                    ];
                }
            }

            $characterData = [];
            foreach ($build->getCharacter() as $character) {
                if (is_object($character) && method_exists($character, 'getName') && method_exists($character, 'getFilename')) {
                    $characterData[] = [
                        'name' => $character->getName(),
                        'filename' => $character->getFilename(),
                    ];
                }
            }

            $bossData = [];
            foreach ($build->getBoss() as $boss) {
                if (is_object($boss) && method_exists($boss, 'getName') && method_exists($boss, 'getFilename')) {
                    $bossData[] = [
                        'name' => $boss->getName(),
                        'filename' => $boss->getFilename(),
                    ];
                }
            }

            $formattedBuilds[] = [
                'id' => $build->getId(),
                'name' => $build->getName(),
                'item' => $itemData,
                'character' => $characterData,
                'boss' => $bossData,
            ];
        }

        return $this->render('profile/builds.html.twig', ['builds' => $formattedBuilds]);
    }


    #[Route('/profile/edit', name: 'app_profile_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('The user must implement PasswordAuthenticatedUserInterface.');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            $passwordValid = $passwordHasher->isPasswordValid($user, $currentPassword);

            if (!$passwordValid) {
                $this->addFlash('error', 'Le mot de passe est incorrect.');
                return $this->redirectToRoute('app_profile_edit');
            }

            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/password/edit', name: 'app_edit_password')]
    public function editPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('The user must implement PasswordAuthenticatedUserInterface.');
        }

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            $passwordValid = $passwordHasher->isPasswordValid($user, $currentPassword);
            if (!$passwordValid) {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                return $this->redirectToRoute('app_edit_password');
            }

            if ($newPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les deux mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_edit_password');
            }

            $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($encodedPassword);

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès.');
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
