<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="app_user", methods={"GET"})
     */
    public function list(): Response
    {
        return $this->render('user/index.html.twig', [
            'UserController' => 'UserController',
        ]);
    }
}
