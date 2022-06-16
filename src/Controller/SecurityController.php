<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_security_login", methods={"GET"})
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }
}