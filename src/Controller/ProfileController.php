<?php

namespace App\Controller;

use App\Repository\BuildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
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
                $itemData[] = [
                    'name' => $item->getName(),
                    'filename' => $item->getFilename(),
                ];
            }


            $formattedBuilds[] = [
                'id' => $build->getId(), // Ajout de l'ID du build
                'name' => $build->getName(),
                'item' => $itemData,
                'boss' => $build->getBoss(),
                'character' => $build->getCharacter(),
            ];
        }

        // return $this->render('profile/index.html.twig');
        return $this->render('profile/builds.html.twig', ['builds' => $formattedBuilds]);
        // return new JsonResponse($formattedBuilds);
    }
}
