<?php

namespace App\Service;

use App\Entity\BlockedCourt;
use App\Entity\Reservation;

class SendEmail
{
    private $configureEmail;
    
    public function __construct(ConfigureEmail $configureEmail)
    {
        $this->configureEmail = $configureEmail;
    }

    public function toUserBlockedCourt(Reservation $reservation, BlockedCourt $blockedCourt)
    {
        $adressFrom = 'contact.passingshot@gmail.com';
        $addressTo = 'contact.passingshot@gmail.com'; // $reservation->getUser()->getEmail();
        $replyTo = 'contact.passingshot@gmail.com';
        $subject = 'Votre réservation (n°'. $reservation->getId() .') est annulée';
        $htmlTemplate = '/email/court_blocked.html.twig' ;
        $context = [
            'reservation' => $reservation,
            'blockedCourt' => $blockedCourt
        ];

        $this->configureEmail->emailConfig($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context);
    }

    public function toUserReservationConfirmation(Reservation $reservation)
    {
        $adressFrom = 'contact.passingshot@gmail.com';
        $addressTo = 'contact.passingshot@gmail.com'; // $reservation->getUser()->getEmail();
        $replyTo = 'contact.passingshot@gmail.com';
        $subject = 'Votre réservation (n°'. $reservation->getId() .') a été validée ';
        $htmlTemplate = '/email/reservation.html.twig' ;
        $context = [
            'reservation' => $reservation
        ];

        $this->configureEmail->emailConfig($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context);
    }
}
