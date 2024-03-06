<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Hebergement;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer ;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/Front', name: 'app_reservation_index_front', methods: ['GET'])]
    public function indexF(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/indexFront.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_reserver', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Hebergement $hebergement,MailerInterface $mailer
    ): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $reservation->setHebergement($hebergement);
            $entityManager->persist($reservation);
            $entityManager->flush();
            $htmlContent = "
            <p><strong>Votre réservation a été confirmée:</strong></p>
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

            return $this->redirectToRoute('app_reservation_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
