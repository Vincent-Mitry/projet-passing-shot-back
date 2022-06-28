<?php

namespace App\Service;

use App\Service\SendEmail;
use App\Entity\Reservation;
use App\Entity\BlockedCourt;
use App\Repository\BlockedCourtRepository;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class BlockedCourtService
{
    private $reservationRepository;
    private $blockedCourtRepository;
    private $sendEmail;
    
    public function __construct(
        ReservationRepository $reservationRepository,
        SendEmail $sendEmail,
        BlockedCourtRepository $blockedCourtRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->sendEmail = $sendEmail;
        $this->blockedCourtRepository = $blockedCourtRepository;
    }

    // Add Confirmation message : "Etes vous sûr(e) de vouloir bloquer le terrain ? Les réservations faisant partie
    // du créneau de date de blocage seront automatiquement annulées."

    public function handleExistingReservationsOnBlocked(
        $court,
        $blockedCourt,
        $blockedStartDatetime,
        $blockedEndDatetime
    ) {
        // Check if existing reservations between blocked court startDatetime and endDatetime
        $reservationsList = $this->reservationRepository->getReservationsInBlockedCourt($court, $blockedStartDatetime, $blockedEndDatetime, $blockedCourt);

        // Set Reservation Status to False
                                      /** @var Reservation */
        foreach ($reservationsList as $reservation) {
            $reservation->setStatus(false);
            
            // Send email with SendEmail service
            $this->sendEmail->toUserBlockedCourt($reservation, $blockedCourt);
        }
    }

    /**
     * Returns an array of court objects that are currently blocked
     *
     * @return array|null
     */
    public function currentCourtsListBlocked(): ?array
    {
        $now = new DateTimeImmutable('now');
        $blockedCourtsList = $this->blockedCourtRepository->getCurrentBlockedCourts($now);

        // We initialize the array that will contain the court objects that are currently blocked
        $currentCourtsBlocked = [];
    
        if(empty($blockedCourtsList)) {
            return null;
        }

        // We insert the court objects that are currently blocked in the array
        foreach ($blockedCourtsList as $blockedCourt) {
            $court = $blockedCourt->getCourt();
            $currentCourtsBlocked[] = $court;
        }

        return $currentCourtsBlocked;
    }

    public function currentBlockedCourts()
    {
        $now = new DateTimeImmutable('now');
        $currentBlockedCourts = $this->blockedCourtRepository->getCurrentBlockedCourts($now);

        if(empty($currentBlockedCourts)) {
            return null;
        }
        
        return$currentBlockedCourts;
    }
    
    public function futureBlockedCourts()
    {
        $now = new DateTimeImmutable('now');
        $currentBlockedCourts = $this->blockedCourtRepository->getFutureBlockedCourts($now);

        if(empty($currentBlockedCourts)) {
            return null;
        }
        
        return$currentBlockedCourts;
    }

    public function pastBlockedCourts()
    {
        $now = new DateTimeImmutable('now');
        $currentBlockedCourts = $this->blockedCourtRepository->getPastBlockedCourts($now);

        if(empty($currentBlockedCourts)) {
            return null;
        }
        
        return$currentBlockedCourts;
    }
}
