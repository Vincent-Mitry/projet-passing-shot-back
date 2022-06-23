<?php

// tests/Controller/MailControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\Transport\TransportInterface;

class MailControllerTest extends WebTestCase
{
    use MailerAssertionsTrait;

    public function testMailIsSentAndContentIsOk()
    {
        $client = $this->createClient();
        //
        // Ici, il nous manque la méthode pour générer un token d'un nouvel utilisateur
        //
        $client->request('POST', '/api/v1/contacts', [], [], ['Content-type' => 'application/json'], '{
            "lastname": "test",
            "firstname": "test",
            "email": "test@test.com",
            "message": "Test POST: HELLO !!!"
            }
        ');
        
        // Attendu code 201
        $this->assertResponseIsSuccessful();

        $this->assertEmailCount(1);

        // $email = $this->getMailerMessage();

        // $this->assertEmailHtmlBodyContains($email, 'Welcome');
        // $this->assertEmailTextBodyContains($email, 'Welcome');
    }
}