<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Admin\EditProductFormType;
use App\Form\Handler\ProductFormHandler;
use App\Utils\Manager\ProductManager;


/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['isDeleted' => false], [ 'id' => 'DESC'], 50); 


        return $this->render('admin/product/list.html.twig', [ 
            'products' => $products
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, ProductFormHandler $productFormHandler , int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($id) {
            $product = $entityManager->getRepository(Product::class)->find($id);

        } else {
            $product = new Product();

        }
        
        $editProductModel = EditProductModel::makeFromProduct($product);


        $form = $this->createForm(EditProductFormType::class, $editProductModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            
            $product = $productFormHandler->processEditForm($editProductModel, $form);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId() ]);
        }
    

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!.');
        }

        return $this->render('admin/product/edit.html.twig', [
            'images'  => $product->getProductImages()->getValues(),
            'product' => $product,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ProductManager $productManager,$id = 0): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        $productManager->remove($product);

        $this->addFlash('warning', 'The product was successfully deleted!.');

        return $this->redirectToRoute('admin_product_list');
    }


}
