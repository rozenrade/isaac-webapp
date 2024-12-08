<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RandomController extends AbstractController
{
    #[Route('/random', name: 'app_random', methods: ['GET'])]
    public function index(ItemRepository $repository, Request $request): Response
    {
        $totalItems = $repository->findAll();

    return $this->render('random/index.html.twig', ['itemList ' => $totalItems]);
    }

}
