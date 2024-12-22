<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\User;
use App\Repository\BuildRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if ($statusController) {
            for ($i = 0; $i < 3; $i++) {
                $randomId = rand(1, count($totalItems));
                $item = $repository->find($randomId);
                if ($item) {
                    $itemSet[] = $item;
                }
            }
        }

        return $this->render('random/index.html.twig', [
            'itemSet' => $itemSet,
            'statusURL' => $statusController
        ]);
    }


    #[Route('/random/save', name: 'app_random_save', methods: ['POST'])]
    public function save(Request $req, ItemRepository $repo): JsonResponse
    {

        //récupérer les id des items
        $data = json_decode($req->getContent(), true);

        if (!$data || !isset($data['items'])) {
            return new JsonResponse(['error' => 'Aucun item sélectionné'], 400);
        }

        //validation des items reçus 
        $newItems = [];
        foreach ($data['items'] as $itemId) {
            $item = $repo->find($itemId);
            if ($item) {
                $newItems[] = $item;
            }
        }

        if (empty($items)) {
            return new JsonResponse(['error' => 'No valid items found'], Response::HTTP_BAD_REQUEST);
        }

        // Créer un nouveau "build" pour l'utilisateur
        $loggedUser = $repo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $newBuild = new Build();

        if(!$loggedUser){
            return new JsonResponse(['error' => 'You are not logged in'], Response::HTTP_UNAUTHORIZED);
        }
        $newBuild->setUtilisateur($loggedUser);

        foreach ($items as $item) { // Ajouter chaque item à la build
            $newBuild->addItem($item); // 
        } 

        return new JsonResponse(['success' => 'Items saved successfully'], Response::HTTP_OK);
    }
}
