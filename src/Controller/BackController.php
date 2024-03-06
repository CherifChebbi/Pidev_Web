<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function userTest(): Response
    {
        return $this->render('back/pages/user.html.twig');
    }
    #[Route('/back', name: 'back')]
    public function backTest(): Response
    {
        return $this->render('back/pages/dashboard.html.twig');
    }
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboardTest(): Response
    {
        return $this->render('back/pages/dashboard.html.twig');
    }
    #[Route('/tables', name: 'tables')]
    public function tablesTest(): Response
    {
        return $this->render('back/pages/tables.html.twig');
    }
    #[Route('/billing', name: 'billing')]
    public function billingTest(): Response
    {
        return $this->render('back/pages/billing.html.twig');
    }
    #[Route('/virtual-reality', name: 'virtual-reality')]
    public function virtual_realityTest(): Response
    {
        return $this->render('back/pages/virtual-reality.html.twig');
    }
    #[Route('/rtl', name: 'rtl')]
    public function rtlTest(): Response
    {
        return $this->render('back/pages/rtl.html.twig');
    }
   
    
}
