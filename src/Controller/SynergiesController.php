<?php

namespace App\Controller;

use App\Repository\SynergyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SynergiesController extends AbstractController
{
    #[Route('/synergie/save/{id}', name: 'app_synergies_save_to_user')]
    public function save(int $id, SynergyRepository $synergyRepository): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Récupérer la synergie par son ID
            $synergy = $synergyRepository->find($id);

            // Si la synergie n'existe pas, rediriger
            if (!$synergy) {
                return $this->redirectToRoute('app_synergies');
            }

            // Ajouter l'utilisateur connecté à la synergie
            $synergy->addUtilisateur($this->getUser());

            // Sauvegarder les modifications via le repository
            $synergyRepository->save($synergy);

            // Redirection après traitement
            return $this->redirectToRoute('app_synergies');
        }

        // Si l'utilisateur n'est pas authentifié, le rediriger vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    #[Route('/synergies/remove/{id}', name: 'app_synergies_remove_from_user', methods: ['POST'])]
    public function removeSynergieFromUser(int $id, Security $security, SynergyRepository $synergyRepository): Response
    {
        // Vérifier que l'utilisateur est connecté
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la synergie par son ID
        $synergy = $synergyRepository->find($id);

        // Si la synergie n'existe pas, rediriger
        if (!$synergy) {
            return $this->redirectToRoute('app_user_show_synergies');
        }

        // Supprimer la synergie de l'utilisateur
        $synergyRepository->removeSynergieFromUser($synergy, $user);

        return $this->redirectToRoute('app_user_show_synergies');
    }
}
