<?php

namespace App\Controller;

use App\Service\Timeslots;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use App\Repository\CourtRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("", name="app_home", methods={"GET"})
     */
    public function home(ClubRepository $clubRepository,
    ReservationRepository $reservationRepository,
    CourtRepository $courtRepository,
    UserRepository $userRepository,
    Timeslots $timeslots,
    Request $request): Response
    {
      
        $test = $timeslots->getAllAvailabletimeslots(date('y/m/d'));

     
        

        return $this->render('main/index.html.twig', [
            'clubs' => $clubRepository->findLastThree(),
            'reservations' => $reservationRepository->findLastThree(),
            'courts' => $courtRepository->findLastThree(),
            'users' => $userRepository->findLastThree(),
            'timeslots' => $timeslots->getAllAvailabletimeslots(date('y/m/d')),         
           
            

        ]);
    }
}
