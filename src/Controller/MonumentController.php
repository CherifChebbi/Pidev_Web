<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonumentController extends AbstractController
{
    #[Route('/monument', name: 'app_monument')]
    public function index(): Response
    {
        return $this->render('monument/index.html.twig', [
            'controller_name' => 'MonumentController',
        ]);
    }
}
