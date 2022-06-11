<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("api/v1", name="api_v1_")
 */
class ReservationController extends AbstractController
{ 
    /**
     * @Route("/reservations/{id}", name="reservations_get_item", methods={"GET"}, requirements={"id"="\d+"})
     * 
     * @return JsonResponse JSON data
     */
    public function reservationsGetItem(Reservation $reservation = null): Response
    {
        if ($reservation === null) {
            return $this->json(['error' => 'No reservation found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($reservation, Response::HTTP_OK, [], ['groups' => 'reservations_get_item']);
    }
}
