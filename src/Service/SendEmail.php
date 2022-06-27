<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmail
{
    
    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    // Function who send email
    public function execute(
    $adressFrom,
    $addressTo,
    $replyTo,
    $subject,
    $htmlTemplate,
    $context,
    MailerInterface $mailer)
    {
        // We define the address of the sender and the address of the recipient
        $from = new Address($adressFrom);
        $to = new Address($addressTo);

        
        $email = new TemplatedEmail();
        $email->from($from)
            ->to($to)
            ->replyTo($replyTo)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);
            


        $loader = new \Twig\Loader\FilesystemLoader('../templates');

        $twigEnv = new Environment($loader);

        $twigBodyRenderer = new BodyRenderer($twigEnv);

        $twigBodyRenderer->render($email);

        // We send the mail

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        
        $mailer = new Mailer($transport);
        
        $mailer->send($email);
    }
}