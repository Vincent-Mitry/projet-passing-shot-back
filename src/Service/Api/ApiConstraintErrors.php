<?php

namespace App\Service\Api;

use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Handles constraint validation errors to be sent to client
 */
class ApiConstraintErrors
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator; 
    }
    
    /**
     * Returns an array of validation constraints errors (null if no errors)
     *
     * @param $object
     * @return null|array
     */
    public function constraintErrorsList($object) : ?array
    {
        // Get error messages from constraints
        $errors = $this->validator->validate($object);
    
        if (count($errors) === 0) { 
            return null;
        }
        
        // Errors List returned to front
        $cleanErrors = [];
    
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $property = $error->getPropertyPath();
            $message = $error->getMessage();

            $cleanErrors[$property][] = $message;
        }

        return $cleanErrors;
    }    
}
