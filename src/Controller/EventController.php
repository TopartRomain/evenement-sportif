<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticipantType;
use App\Entity\Participant;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DistanceCalculator;
use App\Form\EventType;

class EventController extends AbstractController
{
    #[Route('/events', name: 'list_events', methods: ['GET'])]
    public function listEvents(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/create', name: 'create_event', methods: ['GET', 'POST'])]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('list_events');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/events/{id}', name: 'view_event', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function viewEvent(Event $event): Response
    {
        return $this->render('event/view.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/events/{id}/distance', name: 'calculate_distance_to_event', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function calculateDistanceToEvent(
        int $id,
        Request $request,
        DistanceCalculator $distanceCalculator,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $latUtilisateur = $request->query->get('lat');
        $lonUtilisateur = $request->query->get('lon');

        if (!$latUtilisateur || !$lonUtilisateur) {
            return $this->json(['error' => 'Les paramètres "lat" et "lon" sont requis.'], 400);
        }

        $latUtilisateur = (float) $latUtilisateur;
        $lonUtilisateur = (float) $lonUtilisateur;
        $eventLat = $event->getLocationLatitude();
        $eventLon = $event->getLocationLongitude();
        $distance = $distanceCalculator->calculateDistance($latUtilisateur, $lonUtilisateur, $eventLat, $eventLon);

        return new Response(sprintf('L\' utilisateur est à %.2f kilomètres', $distance));
    }
}
