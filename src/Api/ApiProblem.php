<?php

// https://symfonycasts.com/screencast/symfony-rest2/invalid-json-api-problem#play

namespace App\Api;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const TYPE_RESERVATION_ERROR = 'reservation_error'; 
    const TYPE_RESERVATION_NOT_FOUND = 'reservation_not_found'; 

    private static $titles = [
        self::TYPE_VALIDATION_ERROR => 'Une erreur de validation s\'est produite',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
        self::TYPE_RESERVATION_ERROR => 'Une erreur s\'est produite lors de la réservation, le créneau sélectionné n\'est malheureusement plus disponible.',
        self::TYPE_RESERVATION_NOT_FOUND => 'Réservation non trouvée',
    ];

    private $statusCode;

    private $type;

    private $title;

    private $extraErrors = [];

    private $extraData = [];

    public function __construct($statusCode, $type, $extraErrors = null)
    {
        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->extraErrors = $extraErrors;

        if (!isset(self::$titles[$type])) {
            throw new \InvalidArgumentException('No title for type '.$type);
        }

        $this->title = self::$titles[$type];
    }

    // Inserts status, type and title in Array
    public function toArray()
    {
        return array_merge(
            $this->extraData,
            [
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
                'extraErrors' => $this->extraErrors,
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
        return $this->title;
    }
}
