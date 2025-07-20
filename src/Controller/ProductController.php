<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;

final class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product')]
    public function index(ProductsRepository $repository): Response
    {   
        return $this->render('product/index.html.twig', [
            'product' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}',name : "product_show")]
    public function show($id, ProductsRepository $repository) : Response
    {
         return $this->render('product/show.html.twig', [
            'product' => $repository->findOneBy(['id' => $id]),
        ]);
    }

    #[Route('/new',name : "product_new")]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {      
        $product = new Products;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $manager->persist($product);

            $manager->flush();

            return $this->render('product/show.html.twig', [
            "product" => $product
        ]);
        }

         return $this->render('product/new.html.twig', [
            "form" => $form
        ]);
    }
    
    #[Route('/{id<\d+>}/edit',name:"product_edit")]
    public function edit(Products $product,EntityManagerInterface $manager, Request $request): Response
    {

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $manager->flush();

            $this->addFlash(
                'notice',
                'Le Produit a était modifié'
            );

            return $this->render('product/show.html.twig', [
            "product" => $product
        ]);
        }

         return $this->render('product/edit.html.twig', [
            "form" => $form
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: "product_delete")]
    public function delete(Request $request,Products $product, EntityManagerInterface $manager): Response
    {
        if($request->isMethod("POST")){
            
            $manager->remove($product);

            $manager->flush();

            $this->addFlash(
                'notice',
                'Le produit a été supprimé'
            );

            return $this->redirectToRoute("app_product");
        }

        return $this->render('product/delete.html.twig', [
            "id" => $product->getId()
        ]);
    }
}
