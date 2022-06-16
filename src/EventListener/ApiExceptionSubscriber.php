<?php

// https://symfonycasts.com/screencast/symfony-rest2/api-problem-exception#play

namespace App\EventListener;

use App\Service\Api\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles Exceptions in Api Format
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Listens to the Exception Event and intercepts an ApiProblemException 
     *
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {   
        // Gets the exception from the event
        $exception = $event->getThrowable();

        // Checks if the exception is an instance of ApiProblemException
        if (!$exception instanceof ApiProblemException) {
            return;
        }

        $apiProblem = $exception->getApiProblem();

        // Creates Json Response
        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );

        // Sets response headers
        $response->headers->set('Content-Type', 'application/problem+json');

        // Sets to response to the event => this response will be sent to the client
        $event->setResponse($response);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
