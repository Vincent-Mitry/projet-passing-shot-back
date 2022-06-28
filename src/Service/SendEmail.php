<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Reservation;
use App\Entity\BlockedCourt;

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

    public function toAdminContactForm(Contact $contact)
    {
        // Send email with SendEmail service
        $adressFrom = 'contact.passingshot@gmail.com';
        $addressTo = 'contact.passingshot@gmail.com';
        $replyTo = $contact->getEmail();
        $subject = 'Formulaire de contact : '.  $contact->getLastname() . ' ' . $contact->getFirstname();
        $htmlTemplate = '/email/contact.html.twig' ;
        $context = ['contact' => $contact];

        $this->configureEmail->emailConfig($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context);
    }

    public function toUserRegistrationValidation(User $user)
    {
        $adressFrom = 'contact.passingshot@gmail.com';
        $addressTo = 'contact.passingshot@gmail.com';
        $replyTo = $user->getEmail();
        $subject = 'Inscription chez Passing ShO\'t validée ';
        $htmlTemplate = '/email/signup.html.twig' ;
        $context = ['user' => $user];
        
        $this->configureEmail->emailConfig($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context);
    }
}
