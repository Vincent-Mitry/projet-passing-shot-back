<?php

namespace App\Controller\Api\V1;

use App\Service\Api\ApiProblem;
use App\Service\Api\ApiProblemException;
use App\Entity\Reservation;
use App\Service\Api\ApiConstraintErrors;
use App\Service\AvailableTimeslots;
use App\Service\RatingAverage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_RESERVATION_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }

        return $this->json(['reservation' => $reservation], Response::HTTP_OK, [], ['groups' => 'reservations_get_item']);
    }

    /**
     * @Route("/reservations", name="reservations_post", methods={"POST"})
     */
    public function reservationsPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ApiConstraintErrors $apiConstraintErrors,
        AvailableTimeslots $availableTimeslots
    ): Response
     {
        $jsonContent = $request->getContent();

        /** @var Reservation */
        $reservation = $serializer->deserialize($jsonContent, Reservation::class, 'json');

        // Check if the new reservation's timeslots are available
        $checkAvailability = $availableTimeslots->isAvailableForReservation($reservation);
        // If false ==> exception + error message
        if (!$checkAvailability) {  
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_RESERVATION_UNAVAILABLE_SLOT);
            throw new ApiProblemException($apiProblem);
        }

        // Check Validation Constraint Errors
        $constraintErrors = $apiConstraintErrors->constraintErrorsList($reservation);
        if ($constraintErrors !== null) {
            $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR, $constraintErrors);
            throw new ApiProblemException($apiProblem);
        }

        $em = $doctrine->getManager();
        $em->persist($reservation);
        $em->flush();

        $location = $this->generateUrl('api_v1_reservations_get_item', ['id' => $reservation->getId()]);

        return $this->json(['id' => $reservation->getId()], Response::HTTP_CREATED, ['Location' => $location], ['groups' => 'reservations_get_item']);
    }

    /**
     * @Route("/reservations/{id}", name="reservations_put", methods={"PUT"})
     */
    public function reservationsPutItem(
        Reservation $reservation = null,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ApiConstraintErrors $apiConstraintErrors,
        RatingAverage $ratingAverage
    ): Response
     {
        if ($reservation === null) {
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_RESERVATION_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }

        $jsonContent = $request->getContent();

        /** @var Reservation */
        $reservation = $serializer->deserialize($jsonContent, Reservation::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $reservation,
            ['groups' => 'reservations_put_item']
        ]);

        // Check Validation Constraint Errors
        $constraintErrors = $apiConstraintErrors->constraintErrorsList($reservation);
        if ($constraintErrors !== null) {
            $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR, $constraintErrors);
            throw new ApiProblemException($apiProblem);
        }

        $em = $doctrine->getManager();
        $em->flush();

        //adding Rating from Reservation to total court rating
        $ratingAverage->setRatingAverage($reservation->getCourt(), $reservation->getCourtRating());

        return $this->json(null, Response::HTTP_OK);
    }

    /**
     * @Route("/reservations/{id}", name="reservations_delete", methods={"DELETE"})
     */
    public function reservationsDeleteItem(
        Reservation $reservation = null,
        ManagerRegistry $doctrine
    ): Response {
        if ($reservation === null) {
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_RESERVATION_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }

        $em = $doctrine->getManager();
        $em->remove($reservation);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/reservations/available-courts/{date}", name="available_courts_collection", methods={"GET"})
     * 
     * @return JsonResponse JSON data
     */
    public function availableCourtsCollection($date, AvailableTimeslots $availableTimeslots): Response
    {
        $availableTimeslotsByCourt = $availableTimeslots->getAllAvailableTimeslots($date);

        return $this->json(['availableTimeslotsByCourt' => $availableTimeslotsByCourt], Response::HTTP_OK, []);
    }
}
