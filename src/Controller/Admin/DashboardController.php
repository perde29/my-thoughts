<?php

namespace App\Controller\Admin;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard" , name="admin_dashboard_show" )
     */
    public function dashboard()
    {

        return $this->render('admin/pages/dashboard.html.twig');
    }



}