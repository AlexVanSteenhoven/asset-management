<?php

namespace App\Controller;

use App\Entity\ProductGroup;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class ProductController extends AbstractController
{


    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    #[Route('product/create', name: 'create_product')]
    public function create(Request $request)
    {

        $productGroups = $this->entityManager->getRepository(ProductGroup::class)->findAll();
        $status = $this->entityManager->getRepository(Status::class)->findAll();
//    var_dump($request->request->all());die();
//

        return $this->render('product/create.html.twig', [
            'controller_name' => 'ProductController',
            'productGroups' => $productGroups,
            'status'=> $status
        ]);
        
    }


    #[Route('/product', name: 'store_product', methods: 'POST')]
    public function store(Request $request)
    {
        $groupId = $request->request->get('productGroup');
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($groupId);

        $product = new Product();

        $product->setName($request->request->get("name"));
        $product->setProductGroup($group);
        $product->setStatus($request->request->get("status"));
        $this->entityManager->persist($product);


        $this->entityManager->flush();

        return $this->redirectToRoute('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }



    #[Route('product/{id}/show', name: 'show_product')]
    public function show($id, Request $request): Response
    {

        $product = $this->entityManager->getRepository(Product::class)->find($id);
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    }
    
    
    
    
    #[Route('product/{id}/update', name: 'update_product')]
    public function update(string $id, Request $request)
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $groupId = $request->request->get('productGroup');
        $group = $this->entityManager->getRepository(ProductGroup::class)->find($groupId);

        $productGroup = false;
        if($product){
            $productGroup = $product->getProductGroup()?->getId()??false;
            $user = null;
            if(!empty($request->request->get('user',null)))
                $user = $this->entityManager->getRepository(User::class)->find($request->request->get('user',null));
            $product->setName($request->request->get("name"));
            $product->setProductGroup($group);
            $product->setStatus($request->request->get("status"));
            $product->setUser($user);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

        return !$productGroup?$this->redirectToRoute('show_product',['id'=>$id]):$this->redirectToRoute('product_group');
        //@todo: als sam gepusht heeft, product_group aanpassen naar show_group.
//         $this->redirectToRoute('show_group',['id'=>$productGroup]);

    }


    
    #[Route('product/{id}/edit', name: 'store_edit')]
    public function edit(string $id, Request $request)
    {


        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $status = $this->entityManager->getRepository(Status::class)->findAll();
        $groups = $this->entityManager->getRepository(ProductGroup::class)->findAll();
        if($product){

            return $this->render('product/edit.html.twig', [
                'controller_name' => 'ProductController', 
                'product' => $product,
                'users' => $users,
                'status'=>$status,
                'productGroups' => $groups
            ]);
        }
        
        return $this->redirectToRoute('show_product');
        
        
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"POST"})
     */
    #[Route('product/{id}/delete', name: 'destroy_product', methods: 'POST')]
    public function destroy(Request $request, $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        $productGroup = $product->getProductGroup()?->getId() ?? false;

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success', 'Product succesvol verwijderd');
        
        return new JsonResponse(['returnUrl' => $this->generateUrl('show_group', ['id' => $productGroup])]);
    }
    
    #[Route('/product/ajax', name: 'get_products', methods: 'GET')]
    public function getProducts(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $productData = [];
        foreach ($products as $product) {
            $_product = [];
            $_product[] = $product->getId();
            $_product[] = $product->getFirstname();
            $_product[] = $product->getLastname();
            $_product[] = $product->getEmail();
            $productData[] = $_product;
        }

        $output = ["draw" => 1];
        $output["data"] = $productData;
        $output["recordsTotal"] = count($productData);
        $output["recordsFiltered"] = count($productData);
        
        return new JsonResponse($output);
    }
}




