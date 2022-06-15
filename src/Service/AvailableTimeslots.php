<?php

namespace App\Service;

use App\Entity\Court;
use App\Entity\Reservation;
use App\Repository\CourtRepository;
use App\Repository\ReservationRepository;

/**
 * Manages the available time-slots for courts and reservations 
 */
class AvailableTimeslots
{
    private $reservationRepository;
    private $courtRepository;
    
    public function __construct(ReservationRepository $reservationRepository, CourtRepository $courtRepository) {
        $this->reservationRepository = $reservationRepository;
        $this->courtRepository = $courtRepository;
    }
    
    /**
     * Inserts available time-slots for each court on a given date
     *
     * @param string $date Date format (yyyy-mm-dd)
     * @return array Array (the index represents the court's id) containing available time-slots for all courts
     */
    public function getAllAvailableTimeslots($date) : array
    {
        $availableTimeslotsByCourt = [];
        
        $courtList = $this->courtRepository->findAll();

        foreach ($courtList as $court) {
            $courtTimeslots = $this->setAvailableTimeslots($court, $date);

            $availableTimeslotsByCourt[$court->getId()] = $courtTimeslots;
        }

        return $availableTimeslotsByCourt;
    }
    
   /**
    * Sets available time-slots for a court on a given date
    *
    * @param Court $court
    * @param string $date Date format (yyyy-mm-dd)
    * @return array Each entry represents an available time-slot (starting hour)
    */
    public function setAvailableTimeslots(Court $court, $date) : array
    {
        // Array containing the available time slots for the current court
        $availabletimeSlots = [];
        
        // Retrieval of the current's court starting and ending hour (in numerical value)
        $startHour = (int) date_format($court->getStartTime(),'H');
        $endHour = (int) date_format($court->getEndTime(),'H');

        // Inclusion of all time slots in array 
        for ($i = $startHour; $i < $endHour; $i++) {
            $availabletimeSlots[$i] = $i;
        }

        // Retrieval of all current court's reservations for the day
        $reservationList = $this->reservationRepository->getAllReservationsByDateAndCourt($date, $court);
        
        foreach ($reservationList as $reservation) {
            // Retrieval of the current reservation's start time and end time
            $reservationStartHour = (int) date_format($reservation->getStartDatetime(), 'H');
            $reservationEndHour = (int) date_format($reservation->getEndDatetime(), 'H');

            // Removal of each time slot based on the current reservation's start and end time
            for ($i= $reservationStartHour; $i < $reservationEndHour; $i++) { 
                if (($key = array_search($i, $availabletimeSlots)) !== false){
                    unset($availabletimeSlots[$key]);
                }
            }
        }

        return $availabletimeSlots;
    }

    /**
     * Checks if the timeslot for a new reservation is available  
     *
     * @param Reservation $reservation
     * @return boolean true if available for reservation, false if not available
     */
    public function isAvailableForReservation(Reservation $reservation)
    {
        $date = date_format($reservation->getStartDatetime(), 'Y-m-d');
        $court = $reservation->getCourt();
        $startHour = (int) date_format($reservation->getStartDatetime(), 'H');
        $endHour = (int) date_format($reservation->getEndDatetime(), 'H');

        $availableTimeslots = $this->setAvailableTimeslots($court, $date);

        // If the reservation hours are not inside the available time slots ==> return false
        for ($i= $startHour; $i < $endHour; $i++) { 
            if ((array_search($i, $availableTimeslots)) === false){
                return false;
            } 
        }

        return true;
    }
}