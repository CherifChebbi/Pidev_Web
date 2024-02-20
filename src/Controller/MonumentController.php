<?php

namespace App\Controller;

use App\Entity\Ville;

use App\Entity\Monument;
use App\Form\MonumentType;
use App\Repository\MonumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/monument')]
class MonumentController extends AbstractController
{
    #[Route('/', name: 'app_monument_index', methods: ['GET'])]
    public function index(MonumentRepository $monumentRepository): Response
    {
        return $this->render('monument/index.html.twig', [
            'monuments' => $monumentRepository->findAll(),
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
            $entityManager->persist($monument);
            $entityManager->flush();

            return $this->redirectToRoute('app_monument_index', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/{id_monument}', name: 'app_monument_delete', methods: ['POST'])]
    public function delete(Request $request, Monument $monument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monument->getIdMonument(), $request->request->get('_token'))) {
            $entityManager->remove($monument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_monument_index', [], Response::HTTP_SEE_OTHER);
    }
    //---------DELETE SIMPLE----------------
    #[Route('/deleteMonument/{id_monument}', name: 'deleteMonument')]
    public function deleteVille(Monument $monument, EntityManagerInterface $em): Response
    {
        $em->remove($monument);
        $em->flush();
        return $this->redirectToRoute('app_monument_index');
    }
}