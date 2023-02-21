<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Product;
use App\Entity\ProductGroup;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $data = [
            'users' => count($this->entityManager->getRepository(User::class)->findAll()),
            'groups' => count($this->entityManager->getRepository(ProductGroup::class)->findAll()),
            'products' => count($this->entityManager->getRepository(Product::class)->findAll()),
            'logs' => count($this->entityManager->getRepository(Log::class)->findAll())
        ];

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'data' => $data
        ]);
    }

    #[Route('/dashboard/get-users', name: 'ajax_get_users')]
    public function getUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        $userData = [];
        foreach ($users as $user) {
            $_user = [];
            $_user[] = $user->getId();
            $_user[] = $user->getFirstname();
            $_user[] = $user->getLastname();
            $_user[] = $user->getEmail();
            $userData[] = $_user;
        }

        $output = ["draw" => 1 ];
        $output["data"] = $userData;
        $output["recordsTotal"] = count($userData);
        $output["recordsFiltered"] = count($userData);


        return new JsonResponse($output);
    }
}
