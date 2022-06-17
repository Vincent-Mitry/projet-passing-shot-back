<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Service\Api\ApiProblem;
use App\Repository\UserRepository;
use App\Service\Api\ApiConstraintErrors;
use App\Service\Api\ApiProblemException;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * User class 
 * @Route("/api/v1", name="api_v1_")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/users", name="user_list", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function userList(UserRepository $userRepository): Response
    {
        //looking to find all users in userRepository
        $usersList = $userRepository->findAll();

        //expecting a json format response grouping "User_List" collection tag
        return $this->json(['usersList' => $usersList], Response::HTTP_OK, [], ['groups' => 'user_list'],);
    }

    /**
     * @Route ("/users/{id}", name="user_detail", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function userDetail(User $user = null, ReservationRepository $reservationRepository): JsonResponse
    {
        // 404 (not found) personalized response
        if ($user === null) {
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_USER_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }
        //will display upcoming reservations
        $userFutureRes = $reservationRepository->upcomingReservationsByUser($user);
        //will display 5 last reservations
        $userLastRes = $reservationRepository->fiveLastReservationByUser($user);

        //expecting a json format response grouping "User_detail" and User_see_reservations collection tag
        return $this->json([
            'user' => $user,
            'userFutureRes' => $userFutureRes,
            'userLastRes' => $userLastRes
        ], 
            Response::HTTP_OK, [], [
            'groups' => ['user_detail','user_see_reservations', 'past_user_reservations']
        ]);
    }

    /**
     * @Route("/users", name="user_post", methods={"POST"})
     */
    public function userPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ApiConstraintErrors $apiConstraintErrors
    ) {
        // Gathering Json content from $request
        $jsonContent = $request->getContent();

        // We deserialize Json content in $user variable
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // Check Validation Constraint Errors
        $constraintErrors = $apiConstraintErrors->constraintErrorsListUser($user);
        if ($constraintErrors !== null) {
            $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR, $constraintErrors);
            throw new ApiProblemException($apiProblem);
        }
      
        // we save it in DB
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(
            //ID of created User
            ['id' => $user->getId()],
            //status code 201 = created
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_v1_user_detail', ['id' => $user->getId()])
            ]
        );
    }

    /**
     * @Route ("/users/{id}", name="user_update", methods={"PUT"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function userUpdate(
        ApiConstraintErrors $apiConstraintErrors,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        User $user)
    {
        // 404 (not found) personalized response
        if ($user === null) {
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_USER_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }
        //we collect all data from the id in Json format
        $data = $request->getContent();

        //
        $contentToUpdate = $serializer->deserialize($data, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user, ['groups' => 'user_update']]);

        // Check Validation Constraint Errors
        $constraintErrors = $apiConstraintErrors->constraintErrorsListUser($user);
        if ($constraintErrors !== null) {
            $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR, $constraintErrors);
            throw new ApiProblemException($apiProblem);
        }
        
        // we save it in DB
        $em = $doctrine->getManager();
        $em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
