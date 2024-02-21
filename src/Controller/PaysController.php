<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pays')]
class PaysController extends AbstractController
{
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
    //front
    #[Route('/', name: 'app_pays_indexF', methods: ['GET'])]
    public function index(PaysRepository $paysRepository): Response
    {
        return $this->render('pays/indexF.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }
    //Back 
    #[Route('/tables', name: 'app_pays_index', methods: ['GET'])]
    public function indexTables(PaysRepository $paysRepository): Response
    {
        $pays = $paysRepository->findAll();

        return $this->render('pays/index.html.twig', [
            'pays' => $pays,
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
            // Vérification de l'unicité du pays
            $existingPays = $paysRepository->findOneBy(['nom_pays' => $pay->getNomPays()]);
            if ($existingPays) {
                $this->addFlash('error', 'Ce pays existe déjà.');
                return $this->redirectToRoute('app_pays_new'); // Rediriger vers la page d'ajout
            } else {
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
            $entityManager->flush();

            return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pays/edit.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }
    //---------DELETE NOTIFIE-----------
    #[Route('/{id_pays}', name: 'app_pays_delete', methods: ['POST'])]
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
    
}
