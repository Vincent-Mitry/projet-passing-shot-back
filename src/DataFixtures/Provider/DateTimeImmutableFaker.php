<?php

namespace App\DataFixtures\Provider;

use Faker\Generator;
use Faker\Provider\Base;
use Faker\Provider\DateTime;

/**
 * Custom Faker Provider that generates random datetime_immutable objects
 */
class DateTimeImmutableFaker extends Base
{ 
    /**
     * Method that returns a random datetime immutable object
     *
     * @return datetime_immutable
     */
    public function immutableDateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)
    {
        return \DateTimeImmutable::createFromMutable(
            DateTime::dateTimeBetween($startDate, $endDate, $timezone)
        );
    }
}
