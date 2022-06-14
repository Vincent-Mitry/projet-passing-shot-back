<?php

namespace App\Service;

use App\Entity\Court;
use App\Repository\ReservationRepository;

/**
 * Manages the available time-slots for a Court on a given date 
 */
class AvailableTimeslots
{
    private $reservationRepository;
    
    public function __construct(ReservationRepository $reservationRepository) {
        $this->reservationRepository = $reservationRepository;
    }
    
    public function setAvailableTimeslots(Court $court, $date) : array
    {
        $availabletimeSlots = [];
        
        $startHour = date_format($court->getStartTime(),'H');
        $endHour = date_format($court->getEndTime(),'H');

        
        for ($i = $startHour; $i < $endHour; $i++) {
            $availabletimeSlots[$i] = $i;
        }

        $reservationList = $this->reservationRepository->getAllReservationsByDateAndCourt($date, $court);
        
        foreach ($reservationList as $reservation) {
            $reservationStartHour = date_format($reservation->getStartDatetime(), 'H');
            $reservationEndHour = date_format($reservation->getEndDatetime(), 'H');

            for ($i= $reservationStartHour; $i < $reservationEndHour; $i++) { 
                
                if (($key = array_search($i, $availabletimeSlots)) !== false){
                    unset($availabletimeSlots[$key]);
                }
            }
        }

        return $availabletimeSlots;
    }
}