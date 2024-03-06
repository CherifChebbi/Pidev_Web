<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/event/calendar', name: 'app_event_calendar')]
public function eventCalendar(EventRepository $eventRepository): Response
{
    try {
        // Fetch all events from the repository
        $events = $eventRepository->findAll();

        // Check if events exist
        if (empty($events)) {
            throw new \Exception('No events found.');
        }

        // Format the events data for the calendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->getTitre(),
                'lieu' => $event->getLieu(),
                'start' => $event->getDateDebut()->format('Y-m-d'),
                'category' => $event->getIdCategory()->getNom(),
                // You can add more fields like color, url, etc. if needed
            ];
        }
        $data = json_encode($formattedEvents);

        // Pass formatted events to the Twig template
        return $this->render('front/calendar.html.twig', compact('data'));
    } catch (\Exception $e) {
        // Handle any exceptions
        return $this->render('front/error.html.twig', ['error' => $e->getMessage()]);
    }
}

}