<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
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
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $ville = new Ville();
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

        $entityManager->persist($ville);
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
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Récupérer l'ID du pays associé à cette ville
            $paysId = $ville->getPays()->getIdPays();

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
        $em->remove($ville);
        $em->flush();
        return $this->redirectToRoute('app_ville_index');
    }

}
