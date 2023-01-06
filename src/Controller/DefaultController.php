<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\EditProductFormType;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
   
        $entityManager = $this->getDoctrine()->getManager();

        $productList = $entityManager->getRepository(Product :: class)->findAll();

        // dd($productList);die;


        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


    /**
     * @Route("/product-add", name="product_add_old")
     */
    public function productAdd(Request $request)
    {
    

        $product = new Product();
        $product->setTitle("Product".rand(1,100));
        $product->setDescription("smth");
        $product->setPrice(10);
        $product->setQuantity(1);
        // $product->setCreatedAt(new \DateTimeImmutable());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('main_homepage');
        
        /*
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
        */
    }


    /**
     * @Route("/edit-product/{id}", methods="GET|POST" , name="product_edit", requirements={"id"="\d+"} )
     * @Route("/add-product", methods="GET|POST" , name="product_add"  )
     */
    public function editProduct(Request $request, int $id = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($id) {
            $product = $entityManager->getRepository(Product::class)->find($id);

        } else {
            $product = new Product();

        }

        
        $form = $this->createForm(EditProductFormType::class, $product);

                    /*
                    ->add('title', TextType::class)
                    ->add('price', NumberType::class)
                    ->add('quantity', IntegerType::class)
                    ->getForm();
                     
                    */
          
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_edit',['id' => $product->getId()]);

        }
      //  dd($form);die;
      
        return $this->render('default/edit_product.html.twig', [
            'form' => $form->createView()
            
        ]);
    }



}
