<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;

class BlockedCourtService
{
    private $reservationRepository;
    
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function setReservationStatusToFalse(Reservation $reservation)
    {
        $reservation->setStatus(false);
    }

    public function handleExistingReservationsOnBlocked($court, $blockedStartDatetime, $blockedEndDatetime)
    {
        // Check if existing reservations between blocked court startDatetime and endDatetime
        $reservationsList = $this->reservationRepository->getReservationsInBlockedCourt($court, $blockedStartDatetime, $blockedEndDatetime);

        dd($reservationsList);

        // Add Confirmation message : "Etes vous sûr(e) de vouloir bloquer le terrain ? Les réservations faisant partie
        // du créneau de date de blocage seront automatiquement annulées."

        // Set Reservation Status to False

    }
}
