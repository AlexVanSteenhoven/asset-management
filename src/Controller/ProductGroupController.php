<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ProductGroup;

class ProductGroupController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/product/group', name: 'product_group', methods: 'GET')]
    public function index(): Response
    {
        $groups = $this->entityManager->getRepository(ProductGroup::class)->findAll();

        return $this->render('product_group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/product/group/create', name: 'create_group', methods: 'GET')]
    public function create(): Response
    {
        return $this->render('product_group/create.html.twig');
    }

    #[Route('/product/group', name: 'store_group', methods: 'POST')]
    public function store(Request $request)
    {
        $group = new ProductGroup();
        $group->setName($request->request->get("group_name"));


        $this->entityManager->persist($group);
        $this->entityManager->flush();

        $this->addFlash('success', 'Groep succesvol aangemaakt');

        return $this->redirectToRoute('product_group');
    }

    #[Route('/product/group/{id}', name: 'show_group', methods: 'GET')]
    public function show($id): Response
    {
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($id);
        $products = $group->getProducts();
        return $this->render('product_group/show.html.twig', [
            'group' => $group,
            'products' => $products
        ]);
    }

    #[Route('/product/group/{id}/edit', name: 'edit_group', methods: 'GET')]
    public function edit($id): Response
    {
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($id);

        return $this->render('product_group/edit.html.twig', [
            'group' => $group
        ]);
    }

    #[Route('/product/group/{id}', name: 'update_group', methods: 'POST')]
    public function update(Request $request, $id)
    {
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($id);

        $group->setName($request->request->get('group_name'));

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        $this->addFlash('success', 'Groep succesvol aangepast');

        return $this->redirectToRoute('show_group', ['id' => $id]);
    }

    #[Route('/product/group/{id}/delete', name: 'destroy_group', methods: 'POST')]
    public function destroy(Request $request, $id): Response
    {        
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($id);

        $this->entityManager->remove($group);
        $this->entityManager->flush();

        $this->addFlash('success', 'Groep succesvol verwijderd');
        
        return new JsonResponse(['returnUrl'=>$this->generateUrl('product_group')]);
    }

    #[Route('/product/group/ajax', name: 'get_groups', methods: 'GET')]
    public function getGroups(): JsonResponse
    {
        $groups = $this->entityManager->getRepository(ProductGroup::class)->findAll();

        return new JsonResponse([
            "draw" => 1,
            "recordsTotal" => count($groups),
            "recordsFiltered" => count($groups),
            "data" => $groups
        ]);
    }
}
