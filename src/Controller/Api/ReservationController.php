<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\CourtRepository;
use App\Repository\ReservationRepository;
use App\Service\AvailableTimeslots;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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

    /**
     * @Route("/reservations", name="reservations_post", methods={"POST"})
     */
    public function reservationsPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        $jsonContent = $request->getContent();

        /** @var Reservation */
        $reservation = $serializer->deserialize($jsonContent, Reservation::class, 'json');

        // Get error messages from constraints
        $errors = $validator->validate($reservation);

        if (count($errors) > 0) { 
            // Errors List returned to front
            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $message = $error->getMessage();

                $cleanErrors[$property][] = $message;
            }

            return $this->json([$cleanErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $doctrine->getManager();
        $em->persist($reservation);
        $em->flush();

        $location = $this->generateUrl('api_v1_reservations_get_item', ['id' => $reservation->getId()]);

        return $this->json(['reservation' => $reservation], Response::HTTP_CREATED, ['Location' => $location], ['groups' => 'reservations_get_item']);
    }

    /**
     * @Route("/reservations/{id}", name="reservations_put", methods={"PUT"})
     */
    public function reservationsPutItem(
        Reservation $reservation = null,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        if ($reservation === null) {
            throw $this->createNotFoundException(
                'Réservation non trouvée'
            );
        }

        $jsonContent = $request->getContent();

        /** @var Reservation */
        $reservationNew = $serializer->deserialize($jsonContent, Reservation::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $reservation,
            ['groups' => 'reservations_put_item']
        ]);

        // Get error messages from constraints
        $errors = $validator->validate($reservation);

        if (count($errors) > 0) { 
            // Errors List returned to front
            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $message = $error->getMessage();

                $cleanErrors[$property][] = $message;
            }

            return $this->json([$cleanErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $doctrine->getManager();
        $em->flush();

        return $this->json(['reservation' => $reservationNew], Response::HTTP_OK);
    }

    /**
     * @Route("/reservations/{id}", name="reservations_delete", methods={"DELETE"})
     */
    public function reservationsDeleteItem(
        Reservation $reservation = null,
        ManagerRegistry $doctrine
    ): Response
    {
        if ($reservation === null) {
            throw $this->createNotFoundException(
                'Réservation non trouvée'
            );
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
        
        return $this->json([$availableTimeslotsByCourt], Response::HTTP_OK, []);
    }
}
