<?php

namespace App\Service;

use App\Entity\BlockedCourt;
use App\Entity\Court;
use App\Entity\Reservation;
use App\Repository\BlockedCourtRepository;
use App\Repository\CourtRepository;
use App\Repository\ReservationRepository;

/**
 * Manages the available time-slots for courts and reservations 
 */
class ReservedTimeSlots
{
    private $reservationRepository;
    private $courtRepository;
    private $blockedCourtRepository;

    public function __construct(
        ReservationRepository $reservationRepository,
        CourtRepository $courtRepository,
        BlockedCourtRepository $blockedCourtRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->courtRepository = $courtRepository;
        $this->blockedCourtRepository = $blockedCourtRepository;
    }

  

    /**
     * Check the court's reservations and returns the updated available time slots for the day
     *
     * @param string $date
     * @param Court $court
     * @param array $availabletimeSlots
     * @return array
     */
    public function resevedTimeSlot($date, $court)
    {
        // Retrieval of all current court's reservations for the day
        $reservationList = $this->reservationRepository->getAllReservationsByDateAndCourt($date, $court);

        foreach ($reservationList as $reservation) {
            // Retrieval of the current reservation's start time and end time
            $reservationStartHour = (int) date_format($reservation->getStartDatetime(), 'H');
            $reservationEndHour = (int) date_format($reservation->getEndDatetime(), 'H');
            
        }
        dd($reservation);
        return $reservationList;
    }

    
}
