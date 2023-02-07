<?php

namespace App\Form\Handler;

use App\Entity\Order;
use App\Utils\Manager\OrderManager;

class OrderFormHandler
{

    private $orderyManager;

    public function __construct(OrderManager $orderyManager)
    {
        $this->orderyManager = $orderyManager;
    }


    public function processEditForm(Order $order) 
    {
        //$title = ucfirst(strtolower($category->getTitle()));
        /*
        $order = new Order();
        if ($editCategoryModel->id) {
            $order = $this->orderyManager->find($editCategoryModel->id);
        }
        $order->setTitle($editCategoryModel->title);
        */
        
        $this->orderyManager->save($order);

        return $order;

    }


}