<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Synergy;
use App\Form\SynergyType;
use App\Repository\ItemRepository;
use App\Repository\SynergyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/synergie')]
final class AdminSynergyController extends AbstractController
{
    #[Route(name: 'app_admin_synergy_index', methods: ['GET'])]
    public function index(SynergyRepository $synergyRepository): Response
    {
        return $this->render('admin/admin_synergy/index.html.twig', [
            'synergies' => $synergyRepository->findAll(),
        ]);
    }

    // ? add synergy
    #[Route('/new', name: 'app_admin_synergy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ItemRepository $itemRepository): Response
    {
        $synergy = new Synergy();
    
        $form = $this->createForm(SynergyType::class, $synergy);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre la synergie
            $entityManager->persist($synergy);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_admin_synergy_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('Admin/admin_synergy/new.html.twig', [
            'synergy' => $synergy,
            'form' => $form,
        ]);
    }
    

    // ? show synergy
    #[Route('/{id}', name: 'app_admin_synergy_show', methods: ['GET'])]
    public function show(Synergy $synergy): Response
    {
        $items = $synergy->getItem();

        return $this->render('Admin/admin_synergy/show.html.twig', [
            'synergy' => $synergy,
            'items' => $items
        ]);
    }

    // ? edit synergy
    #[Route('/{id}/edit', name: 'app_admin_synergy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Synergy $synergy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SynergyType::class, $synergy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_synergy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_synergy/edit.html.twig', [
            'synergy' => $synergy,
            'form' => $form,
        ]);
    }

    // ? delete synergy
    #[Route('/{id}', name: 'app_admin_synergy_delete', methods: ['POST'])]
    public function delete(Request $request, Synergy $synergy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $synergy->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($synergy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_synergy_index', [], Response::HTTP_SEE_OTHER);
    }
}
