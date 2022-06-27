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
    
    public function __construct(ReservationRepository $reservationRepository, FlashBagInterface $flashbag, BlockedCourtRepository $blockedCourtRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->flashbag = $flashbag;
        $this->blockedCourtRepository = $blockedCourtRepository;
    }

    // Add Confirmation message : "Etes vous sûr(e) de vouloir bloquer le terrain ? Les réservations faisant partie
    // du créneau de date de blocage seront automatiquement annulées."

    public function handleExistingReservationsOnBlocked(
    $court,
    $blockedCourt,
    $blockedStartDatetime,
    $blockedEndDatetime,
    MailerInterface $mailer,
    SendEmail $sendEmail)
    {
        // Check if existing reservations between blocked court startDatetime and endDatetime
        $reservationsList = $this->reservationRepository->getReservationsInBlockedCourt($court, $blockedStartDatetime, $blockedEndDatetime);

        // Set Reservation Status to False
                                      /** @var Reservation */
        foreach ($reservationsList as $reservation) {
            $reservation->setStatus(false);
            
                // Send email with SendEmail service
                $adressFrom = 'contact.passingshot@gmail.com';
                $addressTo = 'contact.passingshot@gmail.com';
                $replyTo = 'contact.passingshot@gmail.com';
                $subject = 'Votre réservation annulée ';
                $htmlTemplate = '/email/court_blocked.html.twig' ;
                $context = [
                    'blockedCourt' => $blockedCourt
                ];
        
                $sendEmail->execute($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context, $mailer);
           
        
        
        
        }
    }
}
