<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class BlockedCourtService
{
    private $reservationRepository;
    
    public function __construct(ReservationRepository $reservationRepository, FlashBagInterface $flashbag)
    {
        $this->reservationRepository = $reservationRepository;
        $this->flashbag = $flashbag;
    }

    public function handleExistingReservationsOnBlocked($court, $blockedStartDatetime, $blockedEndDatetime)
    {
        // Check if existing reservations between blocked court startDatetime and endDatetime
        $reservationsList = $this->reservationRepository->getReservationsInBlockedCourt($court, $blockedStartDatetime, $blockedEndDatetime);

        // Add Confirmation message : "Etes vous sûr(e) de vouloir bloquer le terrain ? Les réservations faisant partie
        // du créneau de date de blocage seront automatiquement annulées."

        // Set Reservation Status to False
                                      /** @var Reservation */
        foreach ($reservationsList as $reservation) {
            $reservation->setStatus(false);
            //TODO : Send Mail To User
        }
    }
}
