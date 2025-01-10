<?php

namespace App\Controller;

use App\Entity\Build;
use App\Repository\BossRepository;
use App\Repository\BuildRepository;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ItemRepository;

class RandomController extends AbstractController
{
    #[Route('/random', name: 'app_random', methods: ['GET'])]
    public function index(
        ItemRepository $itemRepository,
        BossRepository $bossRepository,
        CharacterRepository $characterRepository,
        BuildRepository $buildRepository,
        Request $request,
        SessionInterface $session
    ): Response {
        $totalItems = $itemRepository->findAll();
        $totalBosses = $bossRepository->findAll();
        $totalCharacters = $characterRepository->findAll();

        $url = $request->query->get('status'); // Récupère le paramètre 'status'
        $statusController = ($url === 'random');
        $isSaved = ($url === 'saved'); // Vérifie si l'utilisateur veut sauvegarder

        // ✅ GÉNÉRATION D'UN NOUVEAU BUILD SI "random" EST DANS L'URL
        if ($statusController) {
            $itemSet = $this->getRandomItems($totalItems);
            $selectedBoss = $totalBosses[rand(0, count($totalBosses) - 1)];
            $selectedCharacter = $totalCharacters[rand(0, count($totalCharacters) - 1)];

            $session->set('currentItemSet', [
                'itemSet' => $itemSet,
                'boss' => $selectedBoss,
                'character' => $selectedCharacter
            ]);
        }

        // ✅ RÉCUPÉRATION DU BUILD ACTUEL
        $currentItemSet = $session->get('currentItemSet', null);

        // ✅ SAUVEGARDE DU BUILD LORSQUE L'UTILISATEUR CLIQUE SUR "SAUVEGARDER BUILD"
        if ($isSaved && $currentItemSet) {
            $session->set('savedItemSet', $currentItemSet); // Sauvegarde temporaire en session

            // Vérifie si l'utilisateur est connecté
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $build = new Build();
                $buildCount = $session->get('build_count', 0);
                $build->setName('Build ' . ($buildCount + 1));
                $session->set('build_count', $buildCount + 1);

                $build->setUtilisateur($this->getUser());

                // Ajoute les items, boss et personnage au build
                foreach ($currentItemSet['itemSet'] as $item) {
                    $build->addItem($item);
                }
                $build->addBoss($currentItemSet['boss']);
                $build->addCharacter($currentItemSet['character']);

                $buildRepository->save($build);

                $this->addFlash('success', 'Votre build a été sauvegardé');
            }
        }

        // ✅ SUPPRESSION DES DONNÉES APRÈS AFFICHAGE
        $session->remove('currentItemSet'); // Efface les données après récupération

        return $this->render('random/index.html.twig', [
            'statusURL' => $statusController,
            'currentItemSet' => $currentItemSet, // Envoi des données à Twig
        ]);
    }

    /**
     * 🔥 Fonction pour récupérer 10 items uniques avec image valide
     */
    private function getRandomItems(array $totalItems): array
    {
        $itemSet = [];
        $attempts = 0;
        $maxAttempts = 100;

        while (count($itemSet) < 10 && $attempts < $maxAttempts) {
            $randomId = rand(0, count($totalItems) - 1);
            $item = $totalItems[$randomId];

            $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/items/' . $item->getFilename();
            if (file_exists($imagePath) && !in_array($item, $itemSet)) {
                $itemSet[] = $item;
            }
            $attempts++;
        }

        return $itemSet;
    }
}
