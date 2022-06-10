<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClubController extends AbstractController
{
    /**
     * @Route("/clubs", name="app_club", methods={"GET"})
     */
    public function list(ClubRepository $clubRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }
}
