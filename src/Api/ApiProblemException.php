<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class for Api Exceptions
 */
class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct($apiProblem, $statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->apiProblem = $apiProblem;
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}