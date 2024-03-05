<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Category;
use App\Entity\ReservationEvent;
use App\Form\CategoryType;
use App\Repository\ReservationEventRepository;




#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/front', name: 'app_event_index_front')]
    public function indexFront(EventRepository $eventRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $categoryId = $request->query->get('categoryId');

        // Utilisation de QueryBuilder pour récupérer les événements en fonction de la catégorie
        $events = $eventRepository->createQueryBuilder('e')
            ->leftJoin('e.idCategory', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();

        // Récupération des autres données nécessaires pour le rendu
        $events = $eventRepository->findAll();
        $categories = $categoryRepository->findAll();
        $lieux = $eventRepository->findAllLieux();
        $closestEvents = $eventRepository->findEventsByClosestDate();
        // ... Ajoutez d'autres données nécessaires ici

        return $this->render('front/resEvent.html.twig', [
            'events' => $events,
            'categories' => $categories,
            'lieux' => $lieux,
            'closestEvents' => $closestEvents,
            // ... Ajoutez d'autres variables nécessaires ici
        ]);
    }



    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_event')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $event->setImageEvent($newFilename);
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $newFilename
                );
            }
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'éventuelle modification de l'image
            $imageFile = $form->get('image_event')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                $event->setImageEvent($newFilename);
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads',
                    $newFilename
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/events/lieu', name: 'events_by_lieu')]
public function eventsByLieu(EventRepository $eventRepository)
{
    // Récupérer tous les lieux disponibles
    $lieux = $eventRepository->findAllLieux();

    // Rendre la réponse en utilisant les données récupérées
    return $this->render('front/resEvent.html.twig', [
        'lieux' => $lieux,
        // Autres données que vous souhaitez transmettre à votre template Twig
    ]);
}

    #[Route('/events/closest', name: 'closest_events', methods: ['GET'])]
    public function closestEvents(EventRepository $eventRepository): Response
    {
        $closestEvents = $eventRepository->findEventsByClosestDate();


        return $this->render('front/resEvent.html.twig', [
            'events' => $closestEvents,
        ]);
    }

    #[Route('/sortByPriceAscending', name: 'app_event_sort_by_price_ascending', methods: ['GET'])]
    public function sortByPriceAscending(Request $request, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findByPriceAscending();
    
        return $this->render('front/resEvent.html.twig', [
            'events' => $events,
        ]);
    }

#[Route('/sortByPriceDescending', name: 'app_event_sort_by_price_descending', methods: ['GET'])]
public function sortByPriceDescending(Request $request, EventRepository $eventRepository): Response
{
    $events = $eventRepository->findByPriceDescending();

    return $this->render('front/resEvent.html.twig', [
        'events' => $events,
    ]);
}

#[Route('/sortByTitleAscending', name: 'app_event_sort_by_title_ascending', methods: ['GET'])]
public function sortByTitleAscending(Request $request, EventRepository $eventRepository): Response
{
    $events = $eventRepository->findByTitleAscending();

    return $this->render('front/resEvent.html.twig', [
        'events' => $events,
    ]);
}

#[Route('/sortByTitleDescending', name: 'app_event_sort_by_title_descending', methods: ['GET'])]
public function sortByTitleDescending(Request $request, EventRepository $eventRepository): Response
{
    $events = $eventRepository->findByTitleDescending();

    return $this->render('front/resEvent.html.twig', [
        'events' => $events,
    ]);
}




}
