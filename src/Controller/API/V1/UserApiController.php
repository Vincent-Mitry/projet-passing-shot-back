<?php
namespace App\Controller\API\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * User class * 
 * @Route("/api/v1", name="api_v1_")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/user", name="user_list", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function userList(UserRepository $userRepository): Response
    {
        $usersList = $userRepository->findAll();

        return $this->json($usersList, Response::HTTP_OK, [], ['groups' => 'user_list'],);
    }

    /**
     * @Route ("/user/{id}", name="user_detail", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function userDetail(User $user = null): JsonResponse
    {
        // 404 ?
        if ($user === null) {
            return $this->json(['error' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user_detail']);
    }
}
