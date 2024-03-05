<?php

namespace App\Controller;

use App\Repository\PaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/statistiques', name: 'statistiques')]
    public function statistiques(PaysRepository $paysRepository): Response
    {
        $nombreVillesParPays = $paysRepository->getNombreVillesParPays();
        $paysParContinent = $paysRepository->getPaysParContinent();

        return $this->render('pays/charts.html.twig', [
            'nombreVillesParPays' => $nombreVillesParPays,
            'paysParContinent' => $paysParContinent
        ]);
    }
}
