<?php

namespace App\Controller\Main;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmbedController extends AbstractController
{
 

    public function showSimilarProducts(ProductRepository $productRepository,int $produtCount = 2, int $categoryId = null): Response
    {
        $params = [];
        if ($categoryId) {
            $params['category'] = $categoryId;
        }

        $products = $productRepository->findBy($params, ['id' => 'DESC'],$produtCount);

        return $this->render('main/_embed/_similar_products.html.twig', [
            'products' => $products,
        ]);
    }
}
