<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/pdfuser', name: 'app_userPDF', methods: ['GET'])]
    public function pdf(UserRepository $userRepository, ManagerRegistry $managerRegistry): Response
    {
        $html = $this->renderView('admin/userPDF.html.twig', [
            'users' =>$managerRegistry->getManager()->getRepository(User::class)->findAll(),
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=listUser.pdf ');

        return $response;
    }

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $searchQuery = $request->query->get('search');
        if ($searchQuery) {
            // If there's a search query, get search results
            $users = $userRepository->searchUsers($searchQuery);
        } else {
            // If no search query, paginate all users
            $data = $userRepository->findAll();
            $users = $paginatorInterface->paginate(
                $data,
                $request->query->getInt('page',1),
                2
            );
        }
        
        return $this->render('back/pages/User.html.twig', [
            'users' => $users,
        ]);
    }
    
    /*
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $userRepository->findAll();
        $users = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page',1),
            9
        );
    
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
    */
    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
                }else {
                    // Redirect to app_profile
                    return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
                }
        }

        return $this->renderForm('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    
    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }


#[Route("/admin/verify/{id}", name:"verify_user")]
     
    public function verifyUserAccount($id): Response
    {
        $user = $this->getUser();
       
        
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsVerified(true);
            $em->flush();

            return $this->redirectToRoute('app_admin_index');
        
    }

    #[Route('/ban/{id}', name: 'ban_user')]
   
    public function banUser($id): Response
    {
        $currentUser = $this->getUser();
        
        
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsBanned(true);
            $em->flush();

            return $this->redirectToRoute('app_admin_index');
        
    }

    #[Route("/admin/unban/{id}",name:"unban_user")]
     
     public function UnbanUser($id): Response
     {
         $currentUser = $this->getUser();
         
         
             $em = $this->getDoctrine()->getManager();
             $user = $this->getDoctrine()->getRepository(User::class)->find($id);
             $user->setIsBanned(false);
             $em->flush();
 
             return $this->redirectToRoute('app_admin_index');
         
     }

}

