<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //avant login 
    #[Route('/index1', name: 'index1')]
    public function index1(): Response
    {
        return $this->render('index1.html.twig');
    }
    
    //apres login 
    #[Route('/index2', name: 'index2')]
    public function index2(): Response
    {
        return $this->render('index2.html.twig');
    }
    
}
