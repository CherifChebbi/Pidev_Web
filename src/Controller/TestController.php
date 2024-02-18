<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/front', name: 'front')]
    public function frontTest(): Response
    {
        return $this->render('base_front.html.twig');
    }

    
}
