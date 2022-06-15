<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles Exceptions in Api Format
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {

    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
