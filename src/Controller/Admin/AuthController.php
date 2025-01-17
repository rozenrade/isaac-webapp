<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SignInForm;
use App\Form\SignUpForm;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/signup', name: 'app_signup')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $repository): Response
    {
        $user = new User();
        $existingUser = $repository->findOneBy(['email' => $user->getEmail()]);

        $form = $this->createForm(SignUpForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérification si l'email existe déjà
            // L'email est ce qui permet de vérifier l'authenticité de l'utilisateur
            if ($existingUser) {
                return $this->redirectToRoute('app_signup', ['error' => 'Cet email est déjà utilisé.']);
            }

            // Ajouter le rôle par défaut ROLE_USER
            $user->setRoles(['ROLE_USER']);

            // Hash du mot de passe si Utilisateur créé
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);


            $repository->save($user);

            return $this->redirectToRoute('app_home', ['success' => 'Votre compte a été créé avec succès']);
        }

        return $this->render('auth/signUp.html.twig', ['form' => $form]);
    }


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà authentifié
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_profile');
        }
        $user = new User();
        $form = $this->createForm(SignInForm::class, $user);

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('auth/signin.html.twig', [
            'error' => $error,
            'form' => $form
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_login');
    }
}
