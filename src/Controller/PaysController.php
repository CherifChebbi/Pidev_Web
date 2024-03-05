<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Writer\PngWriter;
use App\Service\OpenWeatherMapService;
use App\Service\StatistiqueService;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/pays')]
class PaysController extends AbstractController
{
//2-Back Affichage des villes lies a un pays
    #[Route('/pays/{id}/villes', name: 'app_pays_villes', methods: ['GET'])]
    public function villes(int $id, PaysRepository $paysRepository): Response
    {
        // Récupérer le pays en fonction de son identifiant
        $pays = $paysRepository->find($id);
       

        if (!$pays) {
            throw $this->createNotFoundException('Pays non trouvé');
        }

        // Récupérer les villes liées à ce pays
        $villes = $paysRepository->findVillesByPaysId($id);

        return $this->render('ville/index.html.twig', [
            'pays' => $pays,
            'villes' => $villes,
           
        ]);
    }
    //---------AFFICHAGE-----------
//1-front Affichage
    #[Route('/', name: 'app_pays_indexF', methods: ['GET'])]
    public function index(PaysRepository $paysRepository, Request $request): Response
    {
        $sortBy = $request->query->get('sortBy', 'nomPays');
        $searchTerm = $request->query->get('q');
        $pays = $paysRepository->findAllOrderedBy($sortBy);
        if ($searchTerm) {
            $pays = $paysRepository->search($searchTerm);
        }
        return $this->render('pays/indexF.html.twig', [
            'pays' => $pays,
            'sortBy' => $sortBy,
        ]);
    }
//2-front Affichage des villes lies a un pays
    #[Route('/pays/{id}/villesF', name: 'app_pays_villesF', methods: ['GET'])]
    public function villesF(VilleRepository $villeRepository,int $id, PaysRepository $paysRepository,Request $request): Response
    {
        // Récupérer le pays en fonction de son identifiant
        $pays = $paysRepository->find($id);

        if (!$pays) {
            throw $this->createNotFoundException('Pays non trouvé');
        }

        // Récupérer les villes liées à ce pays
        $villes = $paysRepository->findVillesByPaysId($id);
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $villes = $villeRepository->search($searchTerm);
        }
        return $this->render('ville/indexF.html.twig', [
            'pays' => $pays,
            'villes' => $villes,
        ]);
    }
//1-Back  Affichage
    #[Route('/tables', name: 'app_pays_index', methods: ['GET'])]
    public function indexTables(PaysRepository $paysRepository, Request $request,StatistiqueService $statistiqueService): Response
    {
        $sortBy = $request->query->get('sortBy', 'nomPays');
        $searchTerm = $request->query->get('q');
        $pays = $paysRepository->findAllOrderedBy($sortBy);
        $statistiques = $statistiqueService->calculerStatistiques();

        if ($searchTerm) {
            $pays = $paysRepository->search($searchTerm);
        }
        return $this->render('pays/index.html.twig', [
            'pays' => $pays,
            'sortBy' => $sortBy,
            'statistiques' => $statistiques,
        ]);
    }
//---------ADD-----------
    #[Route('/new', name: 'app_pays_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PaysRepository $paysRepository): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //upload de l image
            $imageFile = $form->get('img_pays')->getData();     
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $pay->setImgPays($newFilename);
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',
                    $newFilename
                );
            }
            // Vérification de l'unicité du pays
            $existingPays = $paysRepository->findOneBy(['nom_pays' => $pay->getNomPays()]);
            if ($existingPays) {
                $this->addFlash('error', 'Ce pays existe déjà.');
                return $this->redirectToRoute('app_pays_new'); // Rediriger vers la page d'ajout
            } else {
                // Le nombre de villes est initialisé à 0
                $pay->setNbVilles(0);
                $entityManager->persist($pay);
                $entityManager->flush();
                $this->addFlash('success', 'Pays ajouté avec succès.');
                return $this->redirectToRoute('app_pays_index'); // Rediriger vers la liste des pays
            }
        }
        return $this->renderForm('pays/new.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }

//---------SHOW-----------
    #[Route('/{id_pays}', name: 'app_pays_show', methods: ['GET'])]
    public function show(Pays $pay): Response
    {
        return $this->render('pays/show.html.twig', [
            'pay' => $pay,
        ]);
    }
//---------EDIT-----------
    #[Route('/{id_pays}/edit', name: 'app_pays_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pays $pay, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $imageFile = $form->get('img_pays')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid('', true).'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',$newFilename
                );
                $pay->setImgPays($newFilename);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('pays/edit.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }
//---------DELETE NOTIFIE-----------
    #[Route('/{id_pays}', name: 'app_pays_delete',)]
    public function delete(Request $request, Pays $pay, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pay->getIdPays(), $request->request->get('_token'))) {
            $entityManager->remove($pay);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
    }
//---------DELETE SIMPLE----------------
    #[Route('/deletePays/{id_pays}', name: 'deletePays')]
    public function deletePays(Pays $pays, EntityManagerInterface $em): Response
    {
        // Récupérer les villes liées à ce pays
        $villes = $pays->getVilles();

        // Supprimer chaque ville liée à ce pays
        foreach ($villes as $ville) {
            $em->remove($ville);
        }

        // Supprimer le pays lui-même
        $em->remove($pays);
        $em->flush();

        return $this->redirectToRoute('app_pays_index');
    }
    
//---------PDF----------------
    #[Route('/export/pdf', name: 'app_pays_export_pdf', methods: ['GET'])]
    public function exportPdf(PaysRepository $paysRepository): Response
    {
        // Récupérer les données des pays depuis la base de données
        $pays = $paysRepository->findAll();
    
        // Générer le contenu à exporter
        $content = '<h2 style="text-align: center; color: blue;">Liste des pays</h2>'; // Ajout du titre
    
        // Ajout de la date d'exportation
        $content .= '<p style="text-align: right;">Date d\'exportation : ' . date('d/m/Y H:i:s') . '</p>';
    
        $content .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
        $content .= '<tr style="background-color: #f0f0f0;"><th>ID</th><th>Nom</th><th>Image</th><th>Description</th><th>Nombre de villes</th><th>Langue</th></tr>';
        foreach ($pays as $paysItem) {
            $content .= '<tr>';
            $content .= '<td style="padding: 5px;">' . $paysItem->getIdPays() . '</td>';
            $content .= '<td style="padding: 5px;">' . $paysItem->getNomPays() . '</td>';
            $content .= '<td style="padding: 5px;"><img src="' . $this->getImagePath($paysItem->getImgPays()) . '" width="50" height="50"></td>';
            $content .= '<td style="padding: 5px;">' . $paysItem->getDescPays() . '</td>';
            $content .= '<td style="padding: 5px;">' . $paysItem->getNbVilles() . '</td>';
            $content .= '<td style="padding: 5px;">' . $paysItem->getLangue() . '</td>';
            $content .= '</tr>';
        }
        $content .= '</table>';
    
        // Générer et télécharger le fichier PDF
        $pdf = new \TCPDF;
        $pdf->AddPage();
    
        // Ajout du contenu au PDF
        $pdf->writeHTML($content);
    
        // Génération de la date d'exportation
        $dateExport = date('d/m/Y H:i:s');
    
        // Ajout de la date d'exportation en pied de page
        $pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Date d\'exportation : ' . $dateExport, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
        // Téléchargement du fichier PDF
        $pdf->Output('pays_export.pdf', 'D');
    
        return new Response();
    }
    
    // Fonction pour obtenir le chemin complet de l'image à partir de l'URL stockée en base de données
    private function getImagePath($imageName) {
        // Chemin vers le répertoire où les images sont stockées
        $imageDirectory = $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/';
        return $imageDirectory . $imageName;
    }

//---------EXCEL----------------

    #[Route('/export/excel', name: 'app_pays_export_excel', methods: ['GET'])]
    public function exportExcel(PaysRepository $paysRepository): Response
    {
        // Récupérer les données des pays depuis la base de données
        $pays = $paysRepository->findAll();
    
        // Créer un nouveau fichier Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Ajouter les en-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Image');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Nombre de villes');
        $sheet->setCellValue('F1', 'Langue');
    
        // Remplir les données
        $row = 2;
        foreach ($pays as $paysItem) {
            $sheet->setCellValue('A' . $row, $paysItem->getIdPays());
            $sheet->setCellValue('B' . $row, $paysItem->getNomPays());
            // Ajouter le lien vers l'image
            $sheet->setCellValue('C' . $row, $this->getImagePath($paysItem->getImgPays()));
            $sheet->setCellValue('D' . $row, $paysItem->getDescPays());
            $sheet->setCellValue('E' . $row, $paysItem->getNbVilles());
            $sheet->setCellValue('F' . $row, $paysItem->getLangue());
            $row++;
        }
        // Créer le writer pour Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Enregistrer le fichier Excel
        $writer->save('pays_export.xlsx');
        // Retourner une réponse avec le fichier Excel
        return $this->file('pays_export.xlsx');
    }  
    
//---------MAPS--------------------
    
    #[Route('pays/{id}/maps', name: 'afficher_pays_sur_maps')]
    public function afficherPaysSurMaps(int $id, PaysRepository $paysRepository): Response
    {
        // Récupérer les données du pays depuis la base de données
        $pays = $paysRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $nomPays = $pays->getNomPays(); // Supposons que vous avez une méthode getNomPays() dans votre entité Pays
        
        // Générer l'URL Google Maps avec les coordonnées du pays
        $urlGoogleMaps = "https://www.google.com/maps/search/?api=1&query=" . urlencode($nomPays);
        
        // Rediriger vers l'URL Google Maps
        return $this->redirect($urlGoogleMaps);
    }
    
//---------QR CODE--------------------
    #[Route('/pays/qr-code/{id}', name: 'pays_qr_code')]
    public function generateQRCode(int $id, PaysRepository $paysRepository): Response
    {
        $pays = $paysRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $qrCodeContent = $pays->getDescPays();

        $builder = Builder::create()
        ->writer(new PngWriter())
        ->data($qrCodeContent)
        ->encoding(new Encoding('UTF-8'))
        ->size(200)
        ->margin(10)
        ->build();

    return new Response($builder->getString());
    }

//---------API MAP carte/WEATHER-----------------------
    #[Route('pays/{id}', name: 'afficher_pays_sur_carte')]
    public function afficherPaysSurCarte(int $id, PaysRepository $paysRepository, OpenWeatherMapService $weatherService,): Response
    {
        // Récupérer les données du pays depuis la base de données
        $pays = $paysRepository->find($id);
        
        $cityName = $pays->getNomPays(); 
        //$cityName = $request->query->get('city');
        $weatherData = $weatherService->getWeatherByCityName($cityName);

        // Générer la carte Google Maps avec les coordonnées du pays
        $map = $this->generateMap($pays);

        // Retourner la vue avec la carte
        return $this->render('pays/map.html.twig', [
            'pays' => $pays,
            'map' => $map,
            
            'temperature' => $weatherData['main']['temp'] ?? null,
            'weather_condition' => $weatherData['weather'][0]['description'] ?? null,
            'feels_like' => $weatherData['main']['feels_like'] ?? null,
            'pressure' => $weatherData['main']['pressure'] ?? null,
            'humidity' => $weatherData['main']['humidity'] ?? null,
            'wind_speed' => $weatherData['wind']['speed'] ?? null,
            'wind_direction' => $weatherData['wind']['deg'] ?? null,
            'sunrise' => $weatherData['sys']['sunrise'] ?? null,
            'sunset' => $weatherData['sys']['sunset'] ?? null,
    ]);
    }
    private function generateMap(Pays $pays): array
    {
        $map = [
            'center' => [
                'lat' => $pays->getLatitude(),
                'lng' => $pays->getLongitude(),
            ],
            'zoom' => 8,
        ];

        return $map;
    }
//----------------------------------------------------------------------
   




}
