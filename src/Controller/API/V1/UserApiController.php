<?php
namespace App\Controller\API\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * User class 
 * @Route("/api/v1", name="api_v1_")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/user", name="user_list", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function userList(UserRepository $userRepository): Response
    {
        //looking to find all users in userRepository
        $usersList = $userRepository->findAll();

        //expecting a json format response grouping "User_List" collection tag
        return $this->json($usersList, Response::HTTP_OK, [], ['groups' => 'user_list'],);
    }

    /**
     * @Route ("/user/{id}", name="user_detail", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function userDetail(User $user = null): JsonResponse
    {
        // creating 404 responses
        if ($user === null) {
            return $this->json(['error' => 'Membre introuvable'], Response::HTTP_NOT_FOUND);
        }
        //expecting a json format response grouping "User_detail" collection tag
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user_detail']);
    }

     /**
     * @Route("/user", name="user_post", methods={"POST"})
     */
    public function userPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // Gathering Json content in $request
        $jsonContent = $request->getContent();
        
        
        // We deserialize Json content in User Entity
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // Entity Validation
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($user);

        //This will gather any error encountered and place in a array
        if (count($errors) > 0) {

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                $cleanErrors[$property][] = $message;
                
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
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
}
