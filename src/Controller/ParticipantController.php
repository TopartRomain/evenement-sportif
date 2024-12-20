<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    #[Route('/events/{id}/participants/new', name: 'add_participant_event', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function addParticipant(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $participant = new Participant();
        $participant->setEvent($event);

        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Le participant a été ajouté avec succès !');

            return $this->redirectToRoute('view_event', ['id' => $event->getId()]);
        }

        return $this->render('participant/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}
