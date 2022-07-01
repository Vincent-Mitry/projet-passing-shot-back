<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ConfigureEmail
{
    
    private $mailer;
    
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    // Function who send email
    public function emailConfig(
        $adressFrom,
        $addressTo,
        $replyTo,
        $subject,
        $htmlTemplate,
        $context
    ) {
        // We define the address of the sender and the address of the recipient
        $from = new Address($adressFrom);
        $to = new Address($addressTo);

        // We create the email
        $email = new TemplatedEmail();
        $email->from($from)
            ->to($to)
            ->replyTo($replyTo)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);
        
        $this->renderInTwig($email);

        // We send the email
        $this->sendMail($email);        
    }

    // Function for load templates
    public function renderInTwig($email)
    {
        $loader = new \Twig\Loader\FilesystemLoader('../templates');
        $twigEnv = new Environment($loader);
        $twigBodyRenderer = new BodyRenderer($twigEnv);
        return $twigBodyRenderer->render($email);
    }

    public function sendMail($email)
    {
        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $this->mailer = new Mailer($transport);
        
        return $this->mailer->send($email);
    }
}
