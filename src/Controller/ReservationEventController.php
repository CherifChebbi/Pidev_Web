<?php

namespace App\Controller;


use App\Entity\ReservationEvent;
use App\Form\ReservationEventType;
use App\Repository\ReservationEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Email;
use Twilio\Rest\Client;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Mailer\MailerInterface;










#[Route('/reservation/event')]
class ReservationEventController extends AbstractController
{
    
    #[Route('/', name: 'app_reservation_event_index', methods: ['GET'])]
    public function index(ReservationEventRepository $reservationEventRepository): Response
    {
        // Récupérer les réservations avec les événements associés
        $reservationEvents = $reservationEventRepository->createQueryBuilder('r')
            ->leftJoin('r.id_event', 'e') // Faire une jointure avec la table des événements
            ->addSelect('e') // Sélectionner également les données de la table des événements
            ->getQuery()
            ->getResult();

        return $this->render('reservation_event/index.html.twig', [
            'reservation_events' => $reservationEvents,
        ]);
    }

    #[Route('/rf', name: 'app_reservation_event_index_front', methods: ['GET'])]
    public function indexfront(ReservationEventRepository $reservationEventRepository): Response
    {
        $reservationEvent = $reservationEventRepository->findAll();

        return $this->render('front/resEvent.html.twig', [
            'reservation_event' => $reservationEvent,
            'event' => $reservationEvent,
        ]);
    }

    #[Route('/new', name: 'app_reservation_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationEvent = new ReservationEvent();
        $form = $this->createForm(ReservationEventType::class, $reservationEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationEvent);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_event/new.html.twig', [
            'reservation_event' => $reservationEvent,
            'form' => $form,
        ]);
    }

    #[Route('/newfront', name: 'app_reservation_event_new_front', methods: ['GET', 'POST'])]
    public function new_front(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, MailerInterface $mailer): Response
    {
        $reservationEvent = new ReservationEvent();
        $form = $this->createForm(ReservationEventType::class, $reservationEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationEvent);
            $entityManager->flush();


            $this->addFlash('success', 'Réservation effectuée avec succès! Vous recevrez une confirmation par e-mail et par SMS.');
            $transport = Transport::fromDsn('smtp://terranova.noreply@gmail.com:fxdylvrtfelylnpr@smtp.gmail.com:587');
            $mailer = new Mailer($transport); // Remove this line
            $email = (new Email())
                ->from('terranova.noreply@gmail.com')
                ->to('rayensghir7@gmail.com')
                ->subject('Someone write a comment')
                ->html('hello check our web application! someone write a comment ');
            $mailer->send($email);

            return $this->redirectToRoute('app_reservation_event_new_front');

        }

        return $this->render('front/ReservationEvent.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

 


    #[Route('/{id}', name: 'app_reservation_event_show', methods: ['GET'])]
    public function show(ReservationEvent $reservationEvent): Response
    {
        return $this->render('reservation_event/show.html.twig', [
            'reservation_event' => $reservationEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationEvent $reservationEvent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationEventType::class, $reservationEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_event/edit.html.twig', [
            'reservation_event' => $reservationEvent,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_reservation_event_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationEvent $reservationEvent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationEvent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_event_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/pdf', name: 'reservation_pdf', methods: ['GET'])]
public function pdf(ReservationEventRepository $reservationEventRepository)
{
        // Récupérer les données sur les réservations pour chaque client depuis la base de données
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();

        // Créer une instance de Dompdf avec les options par défaut
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Générer le contenu HTML du PDF à partir des données sur les réservations
        $html = $this->renderView('reservation/reservation_pdf.html.twig', [
            'reservations' => $reservations,
        ]);


    // Envoyer le PDF au navigateur
    $dompdf->stream('reservations.pdf', [
        'Attachment' => true,
    ]);

    // Retourner une réponse vide
    return new Response();
}

    

}

