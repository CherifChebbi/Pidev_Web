<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'back')]
    public function backTest(): Response
    {
        return $this->render('back/pages/base_back.html.twig');
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
        return $this->render('back/pages/dashboard.html.twig');
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
    #[Route('/profile', name: 'profile')]
    public function profileTest(): Response
    {
        return $this->render('back/pages/profile.html.twig');
    }
    #[Route('/sign-in', name: 'sign-in')]
    public function sign_inTest(): Response
    {
        return $this->render('back/pages/sign-in.html.twig');
    }
    #[Route('/sign-up', name: 'sign-up')]
    public function sign_upTest(): Response
    {
        return $this->render('back/pages/sign-up.html.twig');
    }
    
}
