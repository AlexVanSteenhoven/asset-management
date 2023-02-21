<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AuthController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login'), Route('/', name: 'home')]
    public function index(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser())
            return $this->redirectToRoute('dashboard');

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/reset-password', name: 'reset-password')]
    public function password_reset(): Response
    {
        return $this->render('auth/reset_password.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    // #[IsGranted('ROLE_ADMIN')]
    #[Route('/register', name: 'register', methods: "POST")]
    public function register(Request $req, UserPasswordHasherInterface $passwordHasher): RedirectResponse
    {
        // Create a new user object
        $user = new User();

        // Catch all request data and store in a array
        $data = [
            'email' => $req->request->get('email'),
            'password' => $passwordHasher->hashPassword($user, $req->request->get('password')),
            'firstname' => $req->request->get('firstname'),
            'lastname' => $req->request->get('lastname'),
        ];

        // Fill in user details from the data-object
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);

        // Save to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('login');
    }
}
