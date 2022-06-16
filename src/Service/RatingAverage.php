<?php

namespace App\Service;


use App\Entity\Court;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Sets Average rating to a movie
 * Copyright *Vincent Mitry*
 */
class RatingAverage
{
    private $manager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->manager = $doctrine->getManager();
    }

    /**
     * Update rating and rateCount fields in DB
     *
     * @param Court $court
     * @param int $newRateValue
     * 
     * @return void
     */
    public function setRatingAverage($court, $newRateValue)
    {
        // Getting the average rating and total count
        $avgRating = $court->getRatingAvg();
        $rateCount = $court->getRatingCount();

        // Recalculation of new Average :
        // (Rating Average * Total Rating Count + New rate Value) / (Total Rating Count + Actual New Count)
        $newRateCount = $rateCount + 1;
        $newAvgRating = ($avgRating * $rateCount + $newRateValue) / ($newRateCount);

        // Update DB
        $court->setRatingAvg($newAvgRating)
              ->setRatingCount($newRateCount);

        $this->manager->flush();
    }
}