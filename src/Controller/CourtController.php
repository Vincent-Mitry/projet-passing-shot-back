<?php

namespace App\Controller;

use App\Repository\CourtRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourtController extends AbstractController
{
    /**
     * @Route("/terrains", name="app_court", methods={"GET"})
     */
    public function list(CourtRepository $courtRepository): Response
    {
        return $this->render('court/index.html.twig', [
            'courts' => $courtRepository->findAll(),
        ]);
    }
}
