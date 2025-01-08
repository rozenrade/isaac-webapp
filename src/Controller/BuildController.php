<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BuildController extends AbstractController
{
    #[Route('profile/build/{id}', name: 'app_build')]
    public function getUserBuilds(): JsonResponse
    {
        return new JsonResponse('test');
    }
}