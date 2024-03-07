<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index_r', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }
    

    #[Route('/new/{id}', name: 'app_reservation_new_r', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Restaurant $restaurant): Response
{
    $reservation = new Reservation();
    $reservation->setRestaurant($restaurant);

    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $email = $form->get('email')->getData(); // Retrieve email from the form

        $entityManager->persist($reservation);
        $entityManager->flush();

        // Retrieve the associated user
        $user = $reservation->getUserId();

        // Check if a user is associated with the reservation
        if ($user) {
            $restaurantName = $restaurant->getnom(); // Replace with the actual method to get the restaurant name

            // Additional information from the reservation entity
            $reservationDate = $reservation->getDate()->format('Y-m-d H:i:s');
            $numberOfPeople = $reservation->getNbrPersonne();

            // Generate the HTML content for the email
            $htmlContent = "
                <p>Votre reservation a ete envoyer avec succes avec les informations suivantes :</p>
                <ul>
                    <li><strong>Nom du restaurant:</strong> $restaurantName</li>
                    <li><strong>La date du reservation:</strong> $reservationDate</li>
                    <li><strong>Nombre des personnes :</strong> $numberOfPeople</li>
                </ul>
            ";

            // Configure the mailer transport (replace with your own email settings)
            $transport = Transport::fromDsn('smtp://majdzari2@gmail.com:xypdvwwnjishfmsn@smtp.gmail.com:587');
            $mailer = new Mailer($transport);

            // Create the email message
            $emailMessage = (new Email())
                ->from('majdzari2@gmail.com')
                ->to($email) // Use the email from the form
                ->subject('Restaurant Reservation Details')
                ->html($htmlContent);

            // Send the email
            $mailer->send($emailMessage);
        }

        return $this->redirectToRoute('app_reservation_index_r', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservation/new.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_reservation_show_r', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit_r', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index_r', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete_r', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index_r', [], Response::HTTP_SEE_OTHER);
    }

















 

    // ... (your existing controller actions)

    #[Route('/reservation/{id}/invoice', name: 'app_reservation_invoice', methods: ['GET'])]
    public function generateInvoice(Reservation $reservation): Response
    {
        // Retrieve the associated restaurant entity
        $restaurant = $reservation->getRestaurant();
    
        // Render PDF using Twig
        $html = $this->renderView('reservation/invoice.html.twig', [
            'reservation' => $reservation,
            'restaurant' => $restaurant, // Pass the restaurant entity to the template
        ]);
    
        // Generate PDF file
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml($html);
    
        // (Optional) Set PDF options
    
        // Render PDF
        $pdf->render();
    
        // Stream PDF file as response
        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice.pdf"',
        ]);
}

}
