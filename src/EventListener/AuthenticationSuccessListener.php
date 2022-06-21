<?php
namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationSuccessListener
{
/**
 * @param AuthenticationSuccessEvent $event
 */
public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
{
    $data = $event->getData();
    $user = $event->getUser();
       

    if (!$user instanceof UserInterface) {
        return ;
    }

    $data['data'] = array(
        'roles' => $user->getUserIdentifier(),
        'id' => $user->getId(),
        
    );

    $event->setData($data);
}
}