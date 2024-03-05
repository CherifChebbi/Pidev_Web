<?php

namespace App\Controller;

use App\Entity\Ville;

use App\Entity\Monument;
use App\Form\MonumentType;
use App\Repository\MonumentRepository;
use App\Repository\VilleRepository;
use App\Service\OpenWeatherMapService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/monument')]
class MonumentController extends AbstractController
{
    #[Route('/monument', name: 'app_monument_index', methods: ['GET'])]
    public function index(MonumentRepository $monumentRepository,Request $request): Response
    {
        $monuments = $monumentRepository->findAll();
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $monuments = $monumentRepository->search($searchTerm);
        }
        return $this->render('monument/index.html.twig', [
            'monuments' =>  $monuments,
        ]);
    }
    #[Route('/', name: 'app_monument_indexF', methods: ['GET'])]
    public function indexF(MonumentRepository $monumentRepository,Request $request): Response
    {
        $monuments = $monumentRepository->findAll();
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $monuments = $monumentRepository->search($searchTerm);
        }
        return $this->render('monument/indexF.html.twig', [
            'monuments' =>  $monuments,
        ]);
    }
/*
    //affichage ville_monuement
    #[Route('/{id_ville}', name: 'liste_villes_monuments', methods: ['GET'])]
    public function listeMonumentsParVille(Ville $ville): Response
    {
        // Récupérez les villes associées à ce pays
        $monument = $ville->getMonuments();

        // Affichez la liste des villes associées à ce pays
        return $this->render('monument/index.html.twig', [
            'monument' => $monument,
            'ville' => $ville,
        ]);
    }
*/

#[Route('/new', name: 'app_monument_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $monument = new Monument();
    $form = $this->createForm(MonumentType::class, $monument);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

         //upload de l image
        $imageFile = $form->get('img_monument')->getData();     
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $monument->setImgMonument($newFilename);
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',
                    $newFilename
                );
            }
        // Récupérez le pays associé à la ville
        $ville = $monument->getVilles();

        // Augmenter le nombre de villes du pays
        $ville->setNbMonuments($ville->getNbMonuments() + 1);
 
        $entityManager->persist($ville);
        $entityManager->persist($monument);
        $entityManager->flush();

        // Récupérer l'ID de la ville associée à ce monument
        $villeId = $monument->getVilles()->getIdVille();

        // Rediriger vers la liste des monuments associés à cette ville spécifique
        return $this->redirectToRoute('app_ville_monuments', ['id' => $villeId]);
    }

    return $this->renderForm('monument/new.html.twig', [
        'monument' => $monument,
        'form' => $form,
    ]);
}


    #[Route('/{id_monument}', name: 'app_monument_show', methods: ['GET'])]
    public function show(Monument $monument): Response
    {
        return $this->render('monument/show.html.twig', [
            'monument' => $monument,
        ]);
    }
/*
    #[Route('/{id_monument}/edit', name: 'app_monument_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Monument $monument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MonumentType::class, $monument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_monument_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('monument/edit.html.twig', [
            'monument' => $monument,
            'form' => $form,
        ]);
    }
*/
#[Route('/{id_monument}/edit', name: 'app_monument_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Monument $monument, EntityManagerInterface $entityManager, MonumentRepository $monumentRepository, VilleRepository $villeRepository): Response
{
    $form = $this->createForm(MonumentType::class, $monument);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        //upload de l'image du monument
        $imageFile = $form->get('img_monument')->getData();     
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            $monument->setImgMonument($newFilename);
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/assets/BACK/img/Pays/',
                $newFilename
            );
        }
        // Récupérer l'ancienne ville associée au monument avant les modifications
        $oldVille = $entityManager->getUnitOfWork()->getOriginalEntityData($monument)['villes'];

        // Récupérer l'ID de la nouvelle ville associée au monument après les modifications
        $newVilleId = $monument->getVilles()->getIdVille();

        // Si la ville associée au monument a été modifiée
        if ($oldVille->getIdVille() !== $newVilleId) {
            // Décrémenter le nombre de monuments de l'ancienne ville
            $oldVille->setNbMonuments($oldVille->getNbMonuments() - 1);

            // Récupérer la nouvelle ville associée au monument
            $newVille = $villeRepository->findOneBy(['id_ville' => $newVilleId]);

            // Incrémenter le nombre de monuments de la nouvelle ville
            $newVille->setNbMonuments($newVille->getNbMonuments() + 1);

            $entityManager->persist($oldVille);
            $entityManager->persist($newVille);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Monument modifié avec succès.');

        // Récupérer l'ID de la ville associée à ce monument
        $villeId = $monument->getVilles()->getIdVille();

        // Rediriger vers la liste des monuments associés à cette ville spécifique
        return $this->redirectToRoute('app_ville_monuments', ['id' => $villeId]);
    }

    return $this->renderForm('monument/edit.html.twig', [
        'monument' => $monument,
        'form' => $form,
    ]);
}

    #[Route('/{id_monument}', name: 'app_monument_delete', methods: ['POST'])]
    public function delete(Request $request, Monument $monument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monument->getIdMonument(), $request->request->get('_token'))) {
            $entityManager->remove($monument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_monument_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/deleteMonument/{id_monument}', name: 'deleteMonument')]
    public function deleteMonument(Monument $monument, EntityManagerInterface $em, VilleRepository $villeRepository): Response
    {
        // Récupérer l'ID de la ville associée à ce monument
        $villeId = $monument->getVilles()->getIdVille();
    
        // Récupérer la ville associée à ce monument
        $ville = $villeRepository->findOneBy(['id_ville' => $villeId]);
    
        // Décrémenter le nombre de monuments de la ville
        $ville->setNbMonuments($ville->getNbMonuments() - 1);
    
        $em->persist($ville);
        $em->remove($monument);
        $em->flush();
    
        // Rediriger vers la liste des monuments associés à cette ville spécifique
        return $this->redirectToRoute('app_ville_monuments', ['id' => $villeId]);
    }

    //-----------------------MAPS--------------------
    #[Route('monument/{id}/maps', name: 'afficher_monument_sur_maps')]
    public function afficherMonumentSurMaps(int $id, MonumentRepository $monumentRepository): Response
    {
        // Récupérer les données du pays depuis la base de données
        $monument = $monumentRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $nomVille = $monument->getNomMonument(); // Supposons que vous avez une méthode getNomPays() dans votre entité Pays
        
        // Générer l'URL Google Maps avec les coordonnées du pays
        $urlGoogleMaps = "https://www.google.com/maps/search/?api=1&query=" . urlencode($nomVille);
        
        // Rediriger vers l'URL Google Maps
        return $this->redirect($urlGoogleMaps);
    }
    //----------------------- QR CODE--------------------
    #[Route('/monument/qr-code/{id}', name: 'monument_qr_code')]
    public function generateQRCode(int $id, MonumentRepository $monumentRepository): Response
    {
        $monument = $monumentRepository->find($id); // récupérer le pays correspondant à l'ID $id depuis la base de données
        $qrCodeContent = $monument->getDescMonument();

        $builder = Builder::create()
        ->writer(new PngWriter())
        ->data($qrCodeContent)
        ->encoding(new Encoding('UTF-8'))
        ->size(200)
        ->margin(10)
        ->build();

    return new Response($builder->getString());
    }
//---------API MAP carte monument-----------------------
    #[Route('monument/{id}', name: 'afficher_monument_sur_carte')]
    public function afficherMonumentSurCarte(int $id, MonumentRepository $monumentRepository): Response
    {
        // Récupérer les données du pays depuis la base de données
        $monument = $monumentRepository->find($id);
        
        $cityName = $monument->getNomMonument(); 

        // Générer la carte Google Maps avec les coordonnées du pays
        $map = $this->generateMap($monument);

        // Retourner la vue avec la carte
        return $this->render('monument/map.html.twig', [
            'monument' => $monument,
            'map' => $map,
    ]);
    }
    private function generateMap(Monument $monument): array
    {
        $map = [
            'center' => [
                'lat' => $monument->getLatitude(),
                'lng' => $monument->getLongitude(),
            ],
            'zoom' => 8,
        ];

        return $map;
    }
//------------------------------
}
