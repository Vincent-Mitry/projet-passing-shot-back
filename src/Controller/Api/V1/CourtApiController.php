<?php
namespace App\Controller\Api\V1;

use App\Entity\Court;
use App\Repository\CourtRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Court class
 * @Route ("/api/v1", name="api_v1")
 */
class CourtApiController extends AbstractController
{
    /**
     * @Route ("/courts", name="court_list", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function courtList(CourtRepository $courtRepository): Response
    {
        return $this->json($courtRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'court_list'],);
    }

    /**
     * @Route ("/courts/{id}", name="court_detail", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function courtDetail(Court $court = null): JsonResponse
    {
        // creating 404 responses
        if ($court === null) {
            return $this->json(['error' => 'Terrain introuvable'], Response::HTTP_NOT_FOUND);
        }

        //expecting a json format response grouping "court_detail" collection tag
        return $this->json(['court' => $court], Response::HTTP_OK, [], ['groups' => 'court_list']);
    }
}