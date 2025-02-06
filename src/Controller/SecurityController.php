<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils                       $authenticationUtils,
                          UserRepository                            $userRepository,
                          Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Get authentication error if any
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last entered username
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the user exists before attempting authentication
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $user = $userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                $error = 'User not found. Please check your username.';
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/home', name: 'app_home')]
    public function home(): Response
    {
        // vérifie si le visiteur est connecté
        if (!$this->getUser()) {
            // sinon redirection vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('base.html.twig');
    }
}
