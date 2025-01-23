<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FallbackController extends AbstractController
{
    public function index(): Response
    {
        // Redirigez ou affichez un message personnalisé
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
}
    