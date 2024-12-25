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
        $url = $request->query->get('status'); // Récupère le paramètre 'status'
        $itemSet = [];

        // Détermine si le statut est "random"
        $statusController = ($url === 'random');

        // Assurer que l'on ait bien 10 items
        if ($statusController) {
            $attempts = 0; // Compteur pour éviter une boucle infinie en cas de trop d'items manquants
            $maxAttempts = 100; // Nombre maximal d'essais pour récupérer 10 items

            while (count($itemSet) < 10 && $attempts < $maxAttempts) {
                // Générer un ID aléatoire
                $randomId = rand(0, count($totalItems) - 1); // On utilise `count($totalItems) - 1` pour éviter les dépassements d'index
                $item = $totalItems[$randomId]; // On récupère l'élément correspondant à cet ID aléatoire

                // Vérifier si l'image existe pour cet item
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/items/' . $item->getFilename();
                $imageExists = file_exists($imagePath);

                // Si l'image existe et qu'on ne l'a pas déjà ajoutée
                if ($imageExists && !in_array($item, $itemSet)) {
                    $itemSet[] = $item;
                }

                $attempts++; // Incrémenter le compteur d'essais
            }
        }

        // Si on n'a pas trouvé 10 items après un certain nombre d'essais, on affiche un message
        if (count($itemSet) < 10) {
            $this->addFlash('warning', 'Moins de 10 items disponibles avec des images.');
        }

        return $this->render('random/index.html.twig', [
            'itemSet' => $itemSet,
            'statusURL' => $statusController
        ]);
    }

}
