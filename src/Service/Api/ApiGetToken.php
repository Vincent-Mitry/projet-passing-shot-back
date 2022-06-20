<?php
namespace App\Service\Api;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiGetToken
{
    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->token = $tokenStorage->getToken();
        $this->user = $this->token->getUser();
        
        dd($tokenStorage); 
}


}