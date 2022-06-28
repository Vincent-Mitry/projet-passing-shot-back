<?php

namespace App\Service;

use App\Service\SendEmail;
use App\Entity\Reservation;
use App\Entity\BlockedCourt;
use App\Repository\BlockedCourtRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class BlockedCourtService
{
    private $reservationRepository;
    private $sendEmail;
    
    public function __construct(ReservationRepository $reservationRepository, SendEmail $sendEmail)
    {
        $this->reservationRepository = $reservationRepository;
        $this->sendEmail = $sendEmail;
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
}
