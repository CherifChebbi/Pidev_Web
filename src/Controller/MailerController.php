<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ReservationEvent;
use App\Entity\Event;





class MailerController extends AbstractController
{
    public function __construct(private MailerInterface $mailer){}

    #[Route('/email')]
    public function sendEmail(Event $event,ReservationEvent $reservationEvent)
    {
        
        // Récupérer les informations sur l'événement réservé
        // $event = $reservationEvent->getIdEvent();
        // // Créer le contenu de l'email avec les détails de la réservation
        // $content = "
        //     <p>Votre réservation a été enregistrée avec succès pour l'événement suivant :</p>
        //     <ul>
        //         <li>Événement : {$event->getTitre()}</li>
        //         <li>Date de début : {$event->getDateDebut()->format('Y-m-d H:i')}</li>
        //         <li>Description : {$event->getDescription()}</li>
        //         <li>Prix : {$event->getPrix()}</li>
        //     </ul>
        // ";

        // Envoyer l'email
        $email = (new Email())
            ->from('terranova.noreply@gmail.com') // Remplacer par votre adresse email
            ->to('terranova.noreply@gmail.com')
            ->subject('Confirmation de réservation')
            ->html('hello');

        $this->mailer->send($email);
    }

}