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

            if ($existingUser) {
                $this->addFlash('error', 'This email is already used.');
                return $this->redirectToRoute('app_signup');
            }

            $user->setRoles(['ROLE_USER']);

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $repository->save($user);

            $this->addFlash('success', 'Successfully created your account');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/signUp.html.twig', ['form' => $form]);
    }


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
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


