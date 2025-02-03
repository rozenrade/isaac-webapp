<?php

namespace App\Controller;

use App\Repository\BuildRepository;
use App\Repository\SynergyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


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
                // Assurez-vous que $item est un objet et qu'il possède les méthodes getName() et getFilename()
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
            ];
        }

        // return $this->render('profile/index.html.twig');

        return $this->render('profile/synergies.html.twig', [
            'synergies' => $formattedSynergies
        ]);

        // return new JsonResponse($formattedBuilds);
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


        // return $this->render('profile/index.html.twig');
        return $this->render('profile/builds.html.twig', ['builds' => $formattedBuilds]);
        // return new JsonResponse($formattedBuilds);
    }

    
}
