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
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\JsonResponse;











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
        // Create a new ReservationEvent instance
        $reservationEvent = new ReservationEvent();
        
        // Create the form for the ReservationEvent
        $form = $this->createForm(ReservationEventType::class, $reservationEvent);
        
        // Handle the form submission
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve email from the form
            $email = $form->get('email')->getData();
    
            // Add flash message before persisting the entity
            $this->addFlash('success', 'Réservation effectuée avec succès! Vous recevrez une confirmation par e-mail et par SMS.');
    
            // Persist the ReservationEvent entity
            $entityManager->persist($reservationEvent);
            $entityManager->flush();
    
            // Additional information from the reservation entity
            $eventTitre = $reservationEvent->getIdEvent()->getTitre(); // Assuming you have a method to get the event name
            $reservationDate = $reservationEvent->getIdEvent()->getDateDebut()->format('Y-m-d'); // Assuming you have a method to get the date
            $eventLieu = $reservationEvent->getIdEvent()->getLieu(); // Assuming you have a method to get the event name
            $eventPrix = $reservationEvent->getIdEvent()->getPrix(); // Assuming you have a method to get the event name
    
            // Generate the HTML content for the email
            $htmlContent = "
            <p><strong>Détails de la réservation :</strong></p>
            <ul style=\"list-style-type: none; padding-left: 0;\">
                <li><strong>Nom de l'événement :</strong> $eventTitre</li>
                <li><strong>Date de l'événement :</strong> $reservationDate</li>
                <li><strong>Lieu :</strong> $eventLieu</li>
                <li><strong>Tarif :</strong> $eventPrix</li>
            </ul>
            ";
    
            // Configure the mailer transport (replace with your own email settings)
            $transport = Transport::fromDsn('smtp://terranova.noreply@gmail.com:fxdylvrtfelylnpr@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
    
            // Create the email message
            $emailMessage = (new Email())
                ->from('terranova.noreply@gmail.com')
                ->to($email) // Use the email from the form
                ->subject('Votre réservation a été confirmée!')
                ->html($htmlContent);
    
            // Send the email
            $mailer->send($emailMessage);
    
            // Send SMS confirmation
            $this->sendConfirmationSMS('+21693435120', 'Terranova vous informe que votre réservation a été confirmée.');
    
            // Redirect to a new page
            return $this->redirectToRoute('app_reservation_event_new_front');
        }
    
        // Render the form template
        return $this->render('front/ReservationEvent.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    private function sendConfirmationSMS(string $phoneNumber, string $message): void
    {
        // Twilio credentials
        $twilioSid = 'ACea6cbad683b84e9e007719a6ce13d791';
        $twilioToken = '54ad15a7a4dcdd067d1dc2d346969c50';
        $twilioNumber = '+19403531823'; // This is the phone number you've purchased from Twilio
    
        // Initialize Twilio client
        $client = new Client($twilioSid, $twilioToken);
    
        try {
            // Send SMS
            $client->messages->create(
                $phoneNumber, // Destination phone number
                [
                    'from' => $twilioNumber,
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            // Handle exception
            // Log or display error message
            echo 'Error: ' . $e->getMessage();
        }
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






    #[Route('/pdf/generate/{id}', name: 'pdf_generate')]
    public function generatePdf(ReservationEvent $reservationEvent): Response
    {
        // Récupérer les données de la réservation d'événement
        $reservationEvent = $this->getDoctrine()->getRepository(ReservationEvent::class)->find($reservationEvent);

        // Rendre le contenu du PDF en utilisant le modèle Twig
        $pdfContent = $this->renderView('reservation_event/pdf.html.twig', [
            'reservation_event' => $reservationEvent,
        ]);

        // Créer une instance de Dompdf
        $options = new Options();
        $options->set('chroot', [__DIR__.'/Librairie', __DIR__.'/PICS', __DIR__.'/PHOTOS']);
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($pdfContent);

        // Rendre le PDF
        $dompdf->render();

        // Retourner le PDF en réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('reservation/event/stats', name: 'stats_reservations')]
    public function statistiques(ReservationEventRepository $reservationEventRepository): Response
    {
        // Get all reservation events from the repository
        $reservationEvents = $reservationEventRepository->findAll();

        // Initialize an array to store reservation counts by event title
        $reservationCounts = [];

        // Calculate reservation counts for each event
        foreach ($reservationEvents as $reservationEvent) {
            $eventTitle = $reservationEvent->getIdEvent()->getTitre();

            // Count reservations by event title
            if (!isset($reservationCounts[$eventTitle])) {
                $reservationCounts[$eventTitle] = 1;
            } else {
                $reservationCounts[$eventTitle]++;
            }
        }

        // Prepare statistics data
        $statistics = $reservationCounts;
        var_dump($statistics);


        // Render the Twig template with the statistics data
        return $this->render('reservation_event/statistiques.html.twig', [
            'reservationData' => $statistics,
        ]);
    }
}






    



