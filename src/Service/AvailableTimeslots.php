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
class AvailableTimeslots
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
            $courtTimeslots = $this->getAvailableTimeslots($court, $date);

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
    public function getAvailableTimeslots(Court $court, $date) : array
    {
        // Array containing the available time slots for the current court
        $availabletimeSlots = [];
        
        // Retrieval of the current's court starting and ending hour (in numerical value)
        $courtStartHour = (int) date_format($court->getStartTime(),'H');
        $courtEndHour = (int) date_format($court->getEndTime(),'H');

        // Inclusion of all time slots in array 
        for ($i = $courtStartHour; $i < $courtEndHour; $i++) {
            $availabletimeSlots[] = $i;
        }

        // Check if court is blocked for the day
        $blockedCourtList = $this->blockedCourtRepository->getBlockedCourtsByDateAndCourt($date, $court);

        foreach ($blockedCourtList as $blockedCourt) {
            // We retrieve the current blocked court start datetime and end datetime objects
            $blockedCourtStartDatetime = $blockedCourt->getStartDatetime();
            $blockedCourtEndDatetime = $blockedCourt->getEndDatetime();

            // We convert the Blocked Court's Datetime Objects in Date Format
            $blockedCourtStartDate = date_format($blockedCourtStartDatetime, 'Y-m-d');
            $blockedCourtEndDate = date_format($blockedCourtEndDatetime, 'Y-m-d');
            // We convert the Blocked Court's Datetime Objects in Numeric Hour Format
            $blockedCourtStartHour = (int) date_format($blockedCourtStartDatetime, 'H');
            $blockedCourtEndHour = (int) date_format($blockedCourtEndDatetime, 'H');

            // We compare the Current Date ($date) with the BlockedCourt's StartDate and EndDate.
            // Depending on the startDate and the endDate, we assign :
            // - the startHour we start removing time slots for the day
            // - the EndHour until we stop removing time slots for the day  

            // If The current date is in between a Blocked Court's startDate and endDate, 
            // all Timeslots are unavailable for the day, so we return an empty array
            if ($blockedCourtStartDate < $date && $date < $blockedCourtEndDate) {
                return $availabletimeSlots = [];
            }

            if ($blockedCourtStartDate == $date && $date < $blockedCourtEndDate) {
                $startHourToRemoveFrom = $blockedCourtStartHour;
                $endHourToStop = $courtEndHour;
            }

            if ($blockedCourtStartDate < $date && $date == $blockedCourtEndDate) {
                $startHourToRemoveFrom = $courtStartHour;
                $endHourToStop = $blockedCourtEndHour;
            }

            if ($blockedCourtStartDate == $date && $date == $blockedCourtEndDate) {
                $startHourToRemoveFrom = $blockedCourtStartHour;
                $endHourToStop = $blockedCourtEndHour;
            }

            // Removal of each time slot based on the current day's start hour to remove until the end hour
            for ($i= $startHourToRemoveFrom; $i < $endHourToStop; $i++) { 
                if (($key = array_search($i, $availabletimeSlots)) !== false){
                    unset($availabletimeSlots[$key]);
                }
            } 
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

        // return clean data "removing" keys and keeping values
        $availabletimeSlots = array_values($availabletimeSlots);

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
        $startDatetime = $reservation->getStartDatetime();
        $startHour = (int) date_format($startDatetime, 'H');
        $endHour = (int) date_format($reservation->getEndDatetime(), 'H');

        $availableTimeslots = $this->getAvailableTimeslots($court, $date);

        // If the reservation hours are not inside the available time slots ==> return false
        for ($i= $startHour; $i < $endHour; $i++) { 
            if ((array_search($i, $availableTimeslots)) === false){
                return false;
            } 
        }

        return true;
    }
}
