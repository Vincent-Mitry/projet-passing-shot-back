<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use App\Repository\CourtRepository;
use App\Service\AvailableTimeslots;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("", name="app_home", methods={"GET"})
     */
    public function home(ClubRepository $clubRepository,
    ReservationRepository $reservationRepository,
    CourtRepository $courtRepository,
    UserRepository $userRepository,
    AvailableTimeslots $availableTimeslots,
    Request $request): Response
    {
      

        

        return $this->render('main/index.html.twig', [
            'clubs' => $clubRepository->findLastThree(),
            'reservations' => $reservationRepository->findLastThree(),
            'courts' => $courtRepository->findLastThree(),
            'users' => $userRepository->findLastThree(),
            'availableTimeslots' => $availableTimeslots->getAllAvailableTimeslots(date('y/m/d')),         
           
            

        ]);
    }
}
