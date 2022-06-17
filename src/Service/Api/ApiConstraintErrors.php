<?php

namespace App\Service\Api;

use App\Entity\Reservation;
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
     * @param Reservation $reservation
     * @return null|array
     */
    public function constraintErrorsList(Reservation $reservation) : ?array
    {
        // Get error messages from constraints
        $errors = $this->validator->validate($reservation);
    
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
