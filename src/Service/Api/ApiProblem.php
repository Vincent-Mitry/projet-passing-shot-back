<?php

// https://symfonycasts.com/screencast/symfony-rest2/invalid-json-api-problem#play

namespace App\Service\Api;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 * 
 * This class lists all API custom error messages
 * 
 */
class ApiProblem
{
    // List of Error Types
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const TYPE_RESERVATION_UNAVAILABLE_SLOT = 'reservation_unavailable_slot'; 
    const TYPE_RESERVATION_NOT_FOUND = 'reservation_not_found'; 

    // List of custom Error Messages
    private static $messages = [
        self::TYPE_VALIDATION_ERROR => 'Une erreur de validation s\'est produite',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
        self::TYPE_RESERVATION_UNAVAILABLE_SLOT => 'Une erreur s\'est produite lors de la réservation, le créneau sélectionné n\'est malheureusement plus disponible ou se trouve en dehors des horaires d\ouverture du terrain.',
        self::TYPE_RESERVATION_NOT_FOUND => 'Réservation non trouvée',
    ];

    private $statusCode;

    private $type;

    private $message;

    private $detailedErrors = [];

    private $extraData = [];

    // Sets ApiProblem parameters => status code, type of error and detailed errors (optional)
    public function __construct($statusCode, $type, $detailedErrors = null)
    {
        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->detailedErrors = $detailedErrors;

        // Checks if Error Type has message
        if (!isset(self::$messages[$type])) {
            throw new \InvalidArgumentException('No message for type '.$type);
        }

        $this->message = self::$messages[$type];
    }

    // Inserts status, type, message and detailed errors in Array
    public function toArray()
    {
        return array_merge(
            $this->extraData,
            [
                'status' => $this->statusCode,
                'type' => $this->type,
                'message' => $this->message,
                'detailedErrors' => $this->detailedErrors,
            ]
        );
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getTitle()
    {
        return $this->message;
    }
}
