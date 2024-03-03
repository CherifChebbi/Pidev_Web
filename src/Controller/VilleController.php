<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use App\Repository\PaysRepository;
use App\Service\OpenWeatherMapService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ville')]
class VilleController extends AbstractController
{
    //FRONT********
    #[Route('/ville/{id}/monumentsF', name: 'app_ville_monumentsF', methods: ['GET'])]
    public function monumentF(int $id, VilleRepository $villeRepository): Response
    {
        // Récupérer le pays en fonction de son identifiant
        $villes = $villeRepository->find($id);

        if (!$villes) {
            throw $this->createNotFoundException('Pays non trouvé');
        }

        // Récupérer les villes liées à ce pays
        $monuments = $villeRepository->findMonumentsByVilleId($id);

        return $this->render('monument/indexF.html.twig', [
            'ville' => $villes,
            'monuments' => $monuments,
        ]);
    }
    //*********** */
    #[Route('/ville/{id}/monuments', name: 'app_ville_monuments', methods: ['GET'])]
    public function villes(int $id, VilleRepository $villeRepository): Response
    {
        // Récupérer la ville  en fonction de son identifiant
        $ville = $villeRepository->find($id);

        if (!$ville) {
            throw $this->createNotFoundException('ville non trouvé');
        }

        // Récupérer les monuments liées 
        $monuments = $villeRepository->findMonumentsByVilleId($id);

        return $this->render('monument/index.html.twig', [
            'ville' => $ville,
            'monuments' => $monuments,
        ]);
    }
    //AFFICHAGE
    #[Route('/ville', name: 'app_ville_index', methods: ['GET'])]
    public function index(VilleRepository $villeRepository,Request $request): Response
    {
        $villes = $villeRepository->findAll();
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $villes = $villeRepository->search($searchTerm);
        }
        return $this->render('ville/index.html.twig', [
            'villes' =>$villes,
        ]);
    }
    #[Route('/', name: 'app_ville_indexF', methods: ['GET'])]
    public function indexf(VilleRepository $villeRepository,Request $request): Response
    {
        $villes = $villeRepository->findAll();
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $villes = $villeRepository->search($searchTerm);
        }
        return $this->render('ville/indexf.html.twig', [
            'villes' =>$villes,
        ]);
    }
/*
    //affichage ville_pays
    #[Route('/{id_pays}', name: 'liste_villes_pays', methods: ['GET'])]
    public function listeVillesParPays(Pays $pays): Response
    {
        // Récupérez les villes associées à ce pays
        $villes = $pays->getVilles();

        // Affichez la liste des villes associées à ce pays
        return $this->render('ville/index.html.twig', [
            'pays' => $pays,
            'villes' => $villes,
        ]);
    }
*/

#[Route('/new', name: 'app_ville_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
{
    $ville = new Ville();
    $form = $this->createForm(VilleType::class, $ville);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        //upload de l'image de la ville
        $imageFile = $form->get('img_ville')->getData();     
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            $ville->setImgVille($newFilename);
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',
                $newFilename
            );
        }

        // Récupérez le pays associé à la ville
        $pays = $ville->getPays();

        // Augmenter le nombre de villes du pays
        $pays->setNbVilles($pays->getNbVilles() + 1);

        // Le nombre de monuments est initialisé à 0
        $ville->setNbMonuments(0);

        
        $entityManager->persist($ville);
        $entityManager->persist($pays);
        $entityManager->flush();

        // Récupérer l'ID du pays associé à cette ville
        $paysId = $ville->getPays()->getIdPays();

        // Rediriger vers la liste des villes associées à ce pays spécifique
        return $this->redirectToRoute('app_pays_villes', ['id' => $paysId]);
    }

    return $this->renderForm('ville/new.html.twig', [
        'ville' => $ville,
        'form' => $form,
    ]);
}
#[Route('/{id_ville}', name: 'app_ville_show', methods: ['GET'])]
public function show(Ville $ville): Response
{
    // Afficher les détails de la ville
    return $this->render('ville/show.html.twig', [
        'ville' => $ville,
    ]);
}


/*
    #[Route('/{id_ville}/edit', name: 'app_ville_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }
*/
#[Route('/{id_ville}/edit', name: 'app_ville_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager, VilleRepository $villeRepository, PaysRepository $paysRepository): Response
{
    $form = $this->createForm(VilleType::class, $ville);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
         //upload de l image
        $imageFile = $form->get('img_ville')->getData();     
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            $ville->setImgVille($newFilename);
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',
                $newFilename
            );
        }
        // Récupères l'ancien pays associé à la ville avant les modifications
        $oldPays = $entityManager->getUnitOfWork()->getOriginalEntityData($ville)['pays'];
        $entityManager->flush();

        // Récupére l'ID du pays associé à cette ville
        $paysId = $ville->getPays()->getIdPays();

        // Si le nom du pays a été modifié
        if ($oldPays->getIdPays() !== $ville->getPays()->getIdPays()) {
            // Incrémente le nombre de villes pour le nouveau pays
            $newPays = $ville->getPays();
            $newPays->setNbVilles($newPays->getNbVilles() + 1);
            $entityManager->persist($newPays);

            // Décrémente le nombre de villes pour l'ancien pays
            $oldPays->setNbVilles($oldPays->getNbVilles() - 1);
            $entityManager->persist($oldPays);

            $entityManager->flush();
        }

        // Rediriger vers la liste des villes associées à ce pays spécifique
        return $this->redirectToRoute('app_pays_villes', ['id' => $paysId]);
    }

    return $this->renderForm('ville/edit.html.twig', [
        'ville' => $ville,
        'form' => $form,
    ]);
}
    #[Route('/{id_ville}', name: 'app_ville_delete', methods: ['POST'])]
    public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getIdVille(), $request->request->get('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
    }
    //---------DELETE SIMPLE----------------
    #[Route('/deleteVille/{id_ville}', name: 'deleteVille')]
    public function deleteVille(Ville $ville, EntityManagerInterface $em): Response
    {
        
        //Récupérez l'auteur associé au ville
        $pays = $ville->getPays();

        if ($pays) {
            // nbr ville-- >0
            $nb_villes = $pays->getNbVilles();
            if ($nb_villes > 0) {
                $pays->setNbVilles($nb_villes - 1);
            }

            // Dissociez le ville de l'auteur
            $ville->setPays(null);

            // Persistez les modifications
            $em->persist($pays);
            $em->persist($ville);
            
            // Supprimez ville
            $em->remove($ville);
            $em->flush();
            
            return $this->redirectToRoute('app_ville_index');
        }
    }
    //-----------------------MAPS--------------------
    #[Route('ville/{id}/maps', name: 'afficher_ville_sur_maps')]
    public function afficherVilleSurMaps(int $id, VilleRepository $villeRepository,): Response
    {
        // Récupérer les données du pays depuis la base de données
        $ville =  $ville = $villeRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $nomVille = $ville->getNomVille(); // Supposons que vous avez une méthode getNomPays() dans votre entité Pays
        
        // Générer l'URL Google Maps avec les coordonnées du pays
        $urlGoogleMaps = "https://www.google.com/maps/search/?api=1&query=" . urlencode($nomVille);
        
        // Rediriger vers l'URL Google Maps
        return $this->redirect($urlGoogleMaps);
    }
    
    //----------------------- QR CODE--------------------
    #[Route('/ville/qr-code/{id}', name: 'ville_qr_code')]
    public function generateQRCode(int $id, VilleRepository $villeRepository): Response
    {
        $ville = $villeRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $qrCodeContent = $ville->getDescVille();

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
    #[Route('ville/{id}', name: 'afficher_ville_sur_carte')]
    public function afficherVilleSurCarte(int $id, VilleRepository $villeRepository, OpenWeatherMapService $weatherService,): Response
    {
        // Récupérer les données du pays depuis la base de données
        $ville = $villeRepository->find($id);
        
        $cityName = $ville->getNomVille(); 
        //$cityName = $request->query->get('city');
        $weatherData = $weatherService->getWeatherByCityName($cityName);

        // Générer la carte Google Maps avec les coordonnées du pays
        $map = $this->generateMap($ville);

        // Retourner la vue avec la carte
        return $this->render('ville/map.html.twig', [
            'ville' => $ville,
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
    private function generateMap(Ville $ville): array
    {
        $map = [
            'center' => [
                'lat' => $ville->getLatitude(),
                'lng' => $ville->getLongitude(),
            ],
            'zoom' => 8,
        ];

        return $map;
    }
//------------------------------
}