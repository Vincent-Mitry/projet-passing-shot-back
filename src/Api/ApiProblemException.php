<?php

// https://symfonycasts.com/screencast/symfony-rest2/api-problem-exception#play

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class for ApiProblem Exceptions
 */
class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct(ApiProblem $apiProblem, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * Get the value of apiProblem
     */ 
    public function getApiProblem()
    {
        return $this->apiProblem;
    }
}
