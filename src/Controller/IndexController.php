<?php

namespace App\Controller;

use App\Repository\SynergyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {

        return $this->render('home/index.html.twig', []);
    }

    #[Route('/synergies', name: 'app_synergies')]
    public function synergy(SynergyRepository $synergyRepository): Response
    {
        $synergies = $synergyRepository->findAll();

        return $this->render('synergies/index.html.twig', ['synergies' => $synergies]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
