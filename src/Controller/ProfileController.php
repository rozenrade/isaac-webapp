<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $loggedUser = $security->getUser();
        $builds = $userRepository->findBuildsByUser($loggedUser);

        return $this->render('profile/index.html.twig', [ 'builds' => $builds ]);
    }
}
