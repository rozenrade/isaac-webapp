<?php

namespace App\Controller;

use App\Entity\Build;
use App\Repository\ItemRepository;
use App\Repository\BossRepository;
use App\Repository\BuildRepository;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            $items = $this->getRandomItems($totalItems);
            $selectedBoss = $totalBosses[rand(0, count($totalBosses) - 1)];
            $selectedCharacter = $totalCharacters[rand(0, count($totalCharacters) - 1)];

            $session->set('currentBuild', [
                'items' => $items,
                'boss' => $selectedBoss,
                'character' => $selectedCharacter
            ]);
        }

        // ✅ RÉCUPÉRATION DU BUILD ACTUEL
        $currentBuild = $session->get('currentBuild', null);

        // ✅ SAUVEGARDE DU BUILD LORSQUE L'UTILISATEUR CLIQUE SUR "SAUVEGARDER BUILD"
        if ($isSaved && $currentBuild) {
            $session->set('savedBuild', $currentBuild); // Sauvegarde temporaire en session

            // Vérifie si l'utilisateur est connecté
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $build = new Build();
                $buildCount = $session->get('build_count', 0);
                $build->setName('Build ' . ($buildCount + 1));
                $session->set('build_count', $buildCount + 1);

                $build->setUtilisateur($this->getUser());

                // Ajoute les items, boss et personnage au build
                foreach ($currentBuild['items'] as $item) {
                    $build->addItem($item);
                }
                $build->addBoss($currentBuild['boss']);
                $build->addCharacter($currentBuild['character']);

                $buildRepository->save($build);
                $session->remove('currentBuild'); // Efface les données après récupération

                $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('random/index.html.twig', [
            'statusURL' => $statusController,
            'currentBuild' => $currentBuild, // Envoi des données à Twig
        ]);
    }

    /**
     * 🔥 Fonction pour récupérer 10 items uniques avec image valide
     */
    private function getRandomItems(array $totalItems)
    {
        $itemSet = [];
        $attempts = 0;
        $maxAttempts = 100;

        if (empty($totalItems)) {
            return []; // Aucune donnée trouvée en BDD
        }

        while (count($itemSet) < 10 && $attempts < $maxAttempts) {
            $randomId = rand(0, count($totalItems) - 1);
            $item = $totalItems[$randomId];

            // 🔹 Nettoyage du filename : Suppression de "public/images/items/"
            $filename = str_replace('public/images/items/', '', $item->getFilename());

            // 🔹 Construction du chemin réel du fichier image
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/items/' . $filename;

            // 🔹 Vérification de l'existence du fichier
            if (file_exists($imagePath) && !in_array($item, $itemSet, true)) {
                $itemSet[] = $item;
            }

            $attempts++;
        }

        return $itemSet;
    }


    #[Route('build/user/{id}', name: 'app_build_user_delete')]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $build = $entityManager->getRepository(Build::class)->find($id);

        if (!$build) {
            $this->addFlash('error', 'The requested build does not exist.');
            return $this->redirectToRoute('app_user_show_builds');
        }

        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('app_user_profile');
        }

        if ($this->isCsrfTokenValid('delete' . $build->getId(), $request->get('_token'))) {
            $entityManager->remove($build);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_show_builds', [], Response::HTTP_SEE_OTHER);
    }
}
