<?php
namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Court class
 * @Route ("/api/v1", name="api_v1")
 */
class CourtApiController extends AbstractController
{
    /**
     * @Route ("/court", name="court_list", methods={GET})
     */
}