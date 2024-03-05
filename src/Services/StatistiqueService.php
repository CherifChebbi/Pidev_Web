<?php

namespace App\Service;

use App\Repository\PaysRepository;
use App\Repository\VilleRepository;
use App\Repository\MonumentRepository;

class StatistiqueService
{
    private $paysRepository;
    private $villeRepository;
    private $monumentRepository;

    public function __construct(PaysRepository $paysRepository, VilleRepository $villeRepository, MonumentRepository $monumentRepository)
    {
        $this->paysRepository = $paysRepository;
        $this->villeRepository = $villeRepository;
        $this->monumentRepository = $monumentRepository;
    }

    public function calculerStatistiques()
    {
        $totalPays = $this->paysRepository->count([]);
        $totalVilles = $this->villeRepository->count([]);
        $totalMonuments = $this->monumentRepository->count([]);
        $paysAvecPlusDeVilles = $this->paysRepository->findPaysAvecPlusDeVilles();
        $villeAvecPlusDeMonuments = $this->villeRepository->findVilleAvecPlusDeMonuments();

        return [
            'nombre_pays' => $totalPays,
            'nombre_villes' => $totalVilles,
            'nombre_monuments' => $totalMonuments,
            'pays_plus_villes' => $paysAvecPlusDeVilles,
            'ville_plus_monuments' => $villeAvecPlusDeMonuments
        ];
    }
}
