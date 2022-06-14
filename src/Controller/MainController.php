<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\CourtRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home(ClubRepository $clubRepository,
    ReservationRepository $reservationRepository,
    CourtRepository $courtRepository,
    UserRepository $userRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'clubs' => $clubRepository->findLastThree(),
            'reservations' => $reservationRepository->findLastThree(),
            'courts' => $courtRepository->findLastThree(),
            'users' => $userRepository->findLastThree(),

        ]);
    }
}
