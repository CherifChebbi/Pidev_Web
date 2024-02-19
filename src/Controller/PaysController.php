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


class PaysController extends AbstractController
{
    //---------AFFICHAGE-----------
    //front
    #[Route('/', name: 'app_pays_index', methods: ['GET'])]
    public function index(PaysRepository $paysRepository): Response
    {
        return $this->render('pays/index.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }
    //Back 
    #[Route('/tables', name: 'tables', methods: ['GET'])]
    public function indexTables(PaysRepository $paysRepository): Response
    {
        return $this->render('back/pages/tables.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }
    //---------ADD-----------
    #[Route('/new', name: 'app_pays_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pay);
            $entityManager->flush();

            return $this->redirectToRoute('tables', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/addPays.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }

    //---------SHOW-----------
    #[Route('/{id_pays}', name: 'app_pays_show', methods: ['GET'])]
    public function show(Pays $pay): Response
    {
        return $this->render('back/pages/show.html.twig', [
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

            return $this->redirectToRoute('tables', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/editPays.html.twig', [
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

        return $this->redirectToRoute('tables', [], Response::HTTP_SEE_OTHER);
    }
    //---------DELETE SIMPLE----------------
    #[Route('/deletePays/{id_pays}', name: 'deletePays')]
    public function deleteAuthor(Pays $pays, EntityManagerInterface $em): Response
    {
        $em->remove($pays);
        $em->flush();
        return $this->redirectToRoute('tables');
    }
    
}
