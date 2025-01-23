<?php

namespace App\Controller;

use App\Entity\Build;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BuildUserController extends AbstractController
{
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
