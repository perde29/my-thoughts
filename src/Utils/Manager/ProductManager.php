<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{

    private $entityManager;
    private $productImagesDir;
    private $productImagesManager;

    public function __construct(EntityManagerInterface $entityManager,productImagesManager $productImagesManager, string $productImagesDir )
    {
        
        $this->entityManager = $entityManager;
        $this->productImagesDir = $productImagesDir;
        $this->productImagesManager = $productImagesManager;

    }


    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);

    }

    public function save(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }


    public function remove()
    {

    }


    public function getProductImagesDir(Product $product)
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }


    public function updateProductImages(Product $product, string $tempImagesFilename = null): Product
    {
        if(!$tempImagesFilename) {
            return $product;
        }


        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImagesManager->saveImageForProduct($productDir, $tempImagesFilename);
        $productImage->setProduct($product);
        
        $product->addProductImage($productImage);

        
        return $product;

    }

}