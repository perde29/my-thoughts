<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ProductFormHandler
{

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->fileSaver     = $fileSaver;

    }


    public function processEditForm(Product $product, Form $form) {


        // dd($product , $form->get('newImage')->getData() );
        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();

        $tempImageFilename = $newImageFile
         ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
         : null;



        $this->productManager->updateProductImages($product, $tempImageFilename);

        $this->productManager->save($product);
        // dd($product);
        return $product;

    }


}