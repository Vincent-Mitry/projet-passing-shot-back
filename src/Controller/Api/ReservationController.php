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

        return $this->json(['movie' => $reservation], Response::HTTP_CREATED, ['Location' => $location], ['groups' => 'reservations_get_item']);
    }
}
