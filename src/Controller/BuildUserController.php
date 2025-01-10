<?php

namespace App\Controller;

use App\Entity\Build;
use App\Form\BuildType;
use App\Repository\BuildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/build/user')]
final class BuildUserController extends AbstractController{
    #[Route(name: 'app_build_user_index', methods: ['GET'])]
    public function index(BuildRepository $buildRepository): Response
    {
        return $this->render('build_user/index.html.twig', [
            'builds' => $buildRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_build_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($build);
            $entityManager->flush();

            return $this->redirectToRoute('app_build_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('build_user/new.html.twig', [
            'build' => $build,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_build_user_show', methods: ['GET'])]
    public function show(Build $build): Response
    {
        return $this->render('build_user/show.html.twig', [
            'build' => $build,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_build_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Build $build, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_build_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('build_user/edit.html.twig', [
            'build' => $build,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_build_user_delete', methods: ['POST'])]
    public function delete(Request $request, Build $build, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$build->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($build);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    }
}
