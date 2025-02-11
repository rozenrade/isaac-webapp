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
            $synergy = $synergyRepository->find($id);

            if (!$synergy) {
                return $this->redirectToRoute('app_synergies');
            }

            $synergy->addUtilisateur($this->getUser());

            $synergyRepository->save($synergy);

            return $this->redirectToRoute('app_synergies');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/synergies/remove/{id}', name: 'app_synergies_remove_from_user', methods: ['POST'])]
    public function removeSynergieFromUser(int $id, Security $security, SynergyRepository $synergyRepository): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $synergy = $synergyRepository->find($id);

        if (!$synergy) {
            return $this->redirectToRoute('app_user_show_synergies');
        }

        $synergyRepository->removeSynergieFromUser($synergy, $user);

        return $this->redirectToRoute('app_synergies');
    }
}

