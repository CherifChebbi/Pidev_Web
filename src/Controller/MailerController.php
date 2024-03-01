<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;




class MailerController extends AbstractController
{
    public function __construct(private MailerInterface $mailer){}

    #[Route('/email')]
    public function sendEmail(
        $to = 'rayensghir7@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>'
    ): Response
    {
        $email = (new Email())
            ->from('terranova.noreply@gmail.com')
            ->to($to)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);

        // Create a simple response to indicate success
        return new Response('Email sent successfully', Response::HTTP_OK);
    }
}