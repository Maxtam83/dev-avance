<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

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

    public function getNewCitation(): array
    {
        try {
            $response = $this->client->request('GET', "http://127.0.0.1:8000/api/citations?page=1", [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]);

            $data = $response->toArray();
        } catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
        }

        // Vérifier si on a des citations
        if (isset($data['member']) && is_array($data['member']) && count($data['member']) > 0) {
            // Sélectionner une citation au hasard
            $citationAleatoire = $data['member'][array_rand($data['member'])];
        }

        return $citationAleatoire;

    }

    #[Route(path: '/home', name: 'app_home')]
    public function home(): Response
    {
        // vérifie si le visiteur est connecté
        if (!$this->getUser()) {
            // sinon redirection vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        try {
            $response = $this->client->request('GET', "http://127.0.0.1:8000/api/citations?page=1", [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
            ]);

            $data = $response->toArray();
        } catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
        }

        // Vérifier si on a des citations
        if (isset($data['member']) && is_array($data['member']) && count($data['member']) > 0) {
            // Sélectionner une citation au hasard
            $citationAleatoire = $data['member'][array_rand($data['member'])];
        }

        return $this->render('base.html.twig', [
            'user' => $this->getUser(),
            'citation' => $citationAleatoire['citation'] ?? 'Aucune citation disponible',
            'auteur' => $citationAleatoire['auteur'] ?? 'Inconnu',
            'existe' => (bool)($citationAleatoire['existe'] ?? false)
            ]);
    }
}
