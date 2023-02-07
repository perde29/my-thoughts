<?php

namespace App\Controller\Main;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{uuid}", name="main_product_show")
     */
    public function show($uuid = null): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->findBy(['uuid' => $uuid]); ;
        $product = $product[0];

        if (!$product) {
            throw new NotFoundHttpException();
        }


        return $this->render('main/product/show.html.twig', [
            'product' => $product
        ]);
    }
}
