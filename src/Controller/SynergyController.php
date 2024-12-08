<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SynergyController extends AbstractController
{
    #[Route('/synergy', name: 'app_synergy')]
    public function index(): Response
    {
        
        return $this->render('synergy/index.html.twig');
    }
}
