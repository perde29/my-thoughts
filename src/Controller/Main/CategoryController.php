<?php

namespace App\Controller\Main;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="main_category_show")
     */
    public function show($slug = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category =  $entityManager->getRepository(Category::class)->findBy(['slug' => $slug]);        
        $category =  $category[0];

        if (!$category) {
            throw new NotFoundHttpException();
        }

        
        $products = $category->getProducts()->getValues();


       // dd($products[0]->getProductImages()->getValues());

        return $this->render('main/category/show.html.twig',[
         'category' => $category,
         'products' => $products 
        ]);


    }


}
