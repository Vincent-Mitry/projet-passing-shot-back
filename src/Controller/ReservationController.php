<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\SendEmail;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/reservations")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("", name="app_reservation")
     */
    public function list(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository, SendEmail $sendEmail): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $reservation->setStatus(true);
        if ($form->isSubmitted() && $form->isValid()) {


            $reservationRepository->add($reservation, true);

            // Send email with SendEmail service
            $sendEmail->toUserReservationConfirmation($reservation);

            $this->addFlash('success', 'Réservation pour ' . $reservation->getId() . ' ajoutée !');

            return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Reservation $reservation = null): Response
    {
        if ($reservation === null) {
            throw $this->createNotFoundException('réservation introuvable');
        }
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_reservation_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Reservation $reservation = null, ReservationRepository $reservationRepository): Response
    {
        if ($reservation === null) {
            throw $this->createNotFoundException('réservation introuvable');
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation, true);

            $this->addFlash('success', 'réservation numéro ' . $reservation->getId() . ' modifié !');

            return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    /**
     * 
     * 
     * @Route("/{id}/suppression", name="app_reservation_deactivate", methods={"GET", "PATCH"}, requirements={"id":"\d+"})
      */
    public function deactivate(Reservation $reservation = null, ReservationRepository $reservationRepository): Response
    {
        if ($reservation === null) {
            throw $this->createNotFoundException('réservation introuvable');
        }
        $status = $reservation->setStatus(false);

        $reservationRepository->add($status, true);

        $this->addFlash('warning', 'La réservation numéro ' . $reservation->getId() . ' a été annulée!');

        return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
    }
}
