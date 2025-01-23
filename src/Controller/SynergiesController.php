<?php 
namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\SynergyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SynergiesController extends AbstractController
{
    #[Route('/synergies', name: 'app_synergies')]
    public function index(ItemRepository $itemRepository): Response
    {
        $synergie1 = [
            $itemRepository->findItemById(1),
            $itemRepository->findItemById(2),
            $itemRepository->findItemById(3),
            $itemRepository->findItemById(4),
            $itemRepository->findItemById(5),
        ];

        $synergie2 = [
            $itemRepository->findItemById(14),
            $itemRepository->findItemById(26),
            $itemRepository->findItemById(46),
            $itemRepository->findItemById(54),
            $itemRepository->findItemById(63),
        ];

        $synergie3 = [
            $itemRepository->findItemById(120),
            $itemRepository->findItemById(239),
            $itemRepository->findItemById(345),
            $itemRepository->findItemById(135),
            $itemRepository->findItemById(256),
        ];

        $synergies = [
            ['item' => $synergie1],
            ['item' => $synergie2],
            ['item' => $synergie3],
        ];

        return $this->render('synergies/index.html.twig', ['synergies' => $synergies]);
    }

    #[Route('/synergie/add', name: 'app_save_synergy_to_user_profile')]
    public function addSynergyToUserProfile(SynergyRepository $synergyRepository, UserRepository $userRepository, Request $request, SessionInterface $session)
    {

    }


}
