<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Hebergement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        $categorie = $this->getDoctrine()->getRepository(Category::class)->findAll();

    return $this->render('front_h/index.html.twig', [
        'categories' => $categorie,
    ]);
    }
    



}
