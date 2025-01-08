<?php

namespace App\Controller;

use App\Entity\Build;
use App\Repository\BuildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ItemRepository;

class RandomController extends AbstractController
{
    #[Route('/random', name: 'app_random', methods: ['GET'])]
    public function index(ItemRepository $itemRepository, BuildRepository $buildRepository,Request $request, SessionInterface $session): Response
    {
        $totalItems = $itemRepository->findAll();
        $url = $request->query->get('status'); // Récupère le paramètre 'status'

        $statusController = ($url === 'random');
        $itemSet = [];

        // Assurer que l'on ait bien 10 items
        if ($statusController) {
            $attempts = 0;
            $maxAttempts = 100;

            while (count($itemSet) < 10 && $attempts < $maxAttempts) {
                $randomId = rand(0, count($totalItems) - 1);
                $item = $totalItems[$randomId];

                $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/items/' . $item->getFilename();
                $imageExists = file_exists($imagePath);

                if ($imageExists && !in_array($item, $itemSet)) {
                    $itemSet[] = $item;
                }

                $attempts++;
            }

            if (count($itemSet) < 10) {
                $this->addFlash('warning', 'Moins de 10 items disponibles avec des images.');
            }

            // Sauvegarde le set actuel dans la session
            $session->set('currentItemSet', $itemSet);
        }

        // Si l'utilisateur clique sur "sauvegarder"
        if ($url === 'saved') {
            $currentSet = $session->get('currentItemSet', []);
            $session->set('savedItemSet', $currentSet);

            if($this->isGranted('IS_AUTHENTICATED_FULLY')){
                $build = new Build();
                $build->setName('Build ' . count($currentSet));
                $build->setUtilisateur($this->getUser());

                foreach ($currentSet as $item) {
                    $build->addItem($item);
                }

                $buildRepository->save($build);
                $this->addFlash('success', 'Votre build a été sauvegardé');
            }

            return $this->redirectToRoute('app_saved_items'); // Redirection vers une autre page
        }

        return $this->render('random/index.html.twig', [
            'itemSet' => $itemSet,
            'statusURL' => $statusController,
        ]);
    }

}
