<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Restaurant;
use App\Form\CommentsType;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use DateTime as GlobalDateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/restaurant')]
class RestaurantController extends AbstractController
{
    #[Route('/', name: 'app_restaurant_index', methods: ['GET'])]
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findAll(),
        ]);
    }

    #[Route('/front', name: 'app_restaurant_front', methods: ['GET'])]
    public function front(Request $request, RestaurantRepository $restaurantRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $location = $request->query->get('location');
        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');
    
        // Ensure $searchTerm is not null
        $searchTerm = $searchTerm ?? '';
        $location = $location ?? ''; // Set a default value if null
        $minPrice = is_numeric($minPrice) ? (int) $minPrice : null; // Ensure minPrice is either null or an integer
        $maxPrice = is_numeric($maxPrice) ? (int) $maxPrice : null; // Ensure maxPrice is either null or an integer
    
        // Add $location and $priceRange to your repository method
        $restaurants = $restaurantRepository->advancedSearch($searchTerm, $location, $minPrice, $maxPrice);
    
        return $this->render('front_r/index.html.twig', [
            'restaurants' => $restaurants,
            'searchTerm' => $searchTerm,
        ]);
    }
   

   
    

    #[Route('/new', name: 'app_restaurant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $restaurant->setImage($newFilename);
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $newFilename
                );
            }
            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurant/new.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form,
        ]);
    }

    #[Route('/front/{id}', name: 'app_restaurant_front_show', methods: ['GET', 'POST'])]
    public function detail(Restaurant $restaurant, Request $request, RestaurantRepository $rep): Response
    { 
        $restaurant = $rep->findOneBy(['id' => $request->get('id')]);


        //comment 
        //creation comment 
        $comm = new Comments;


        $commentForm = $this->createForm(CommentsType::class, $comm);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){

        $comm->setCreatedAt(new DateTimeImmutable());
        $comm->setRetsaurant($restaurant);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comm);
        $em->flush();
        $this->addFlash('message', 'Votre commentaire a bien ete envoyer');
        return $this->redirectToRoute('app_restaurant_front_show', ['id'=>$restaurant->getId()]);



        }





    return $this->render('front_r/detail.html.twig', [
        'restaurant' => $restaurant,
        'commentForm'=> $commentForm->createView()
    ]);
}
    #[Route('/{id}', name: 'app_restaurant_show', methods: ['GET'])]
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_restaurant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Restaurant $restaurant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurant/edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_delete', methods: ['POST'])]
    public function delete(Request $request, Restaurant $restaurant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_restaurant_index', [], Response::HTTP_SEE_OTHER);
    }






    #[Route('/{id}/like', name: 'likePublication', methods: ['POST'])]
    public function likePublication($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurant = $entityManager->getRepository(Restaurant::class)->find($id);
    
        if (!$restaurant) {
            throw $this->createNotFoundException('Publication not found');
        }
    
        // Increment likes for the publication
        $restaurant->incrementLikes();
    
        $entityManager->flush();
    
        return new JsonResponse(['likes' => $restaurant->getLikes()]);
    }
    

    





}
