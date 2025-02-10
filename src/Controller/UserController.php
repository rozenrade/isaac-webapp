<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
    // La route qui supprime un utilisateur basé sur son ID
    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    #[ParamConverter('user', class: 'App\Entity\User')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // Suppression de l'utilisateur
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Redirection après suppression
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
