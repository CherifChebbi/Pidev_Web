<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/front')]
class FrontController extends AbstractController
{
    
    #[Route('/restaurant', name: 'user_restaurant_index', methods: ['GET'])]
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'restaurants' => $restaurantRepository->findAll(),
        ]);
    }
}
