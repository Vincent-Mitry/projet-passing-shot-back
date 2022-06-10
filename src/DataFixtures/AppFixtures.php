<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Club;
use App\Entity\User;
use App\Entity\Court;
use DateTimeImmutable;
use App\Entity\Reservation;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\DateTimeImmutableFaker;



class AppFixtures extends Fixture
{
    /**
     * Connection from database
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        // Get connexion from database (DBAL ~= PDO)
        $this->connection = $connection;
    }
    
    /**
     * Truncates tables in database and resets IDs to 1
     */
    private function truncate()
    {
        // Désactivation la vérification des contraintes FK
        // Disable constraint check on FKs
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // Truncate tables
        $this->connection->executeQuery('TRUNCATE TABLE court');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        $this->connection->executeQuery('TRUNCATE TABLE reservation');
        $this->connection->executeQuery('TRUNCATE TABLE club');
        $this->connection->executeQuery('TRUNCATE TABLE blocked_court');
        // etc.
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new DateTimeImmutableFaker($faker));

        //User
        // Creates super_admin
        $superAdmin = new User();
        $superAdmin->setLastname($faker->lastName())
                   ->setFirstname($faker->firstName())
                   ->setEmail('superadmin@superadmin.com')
                   ->setGender($faker->randomElement(['homme', 'femme', 'neutre']))
                   ->setLevel($faker->numberBetween(1,3))
                   ->setPhone('0123456789')
                   ->setPassword('superadmin')
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin);

        // Creates admin 
        $admin = new User();
        $admin->setLastname($faker->lastName())
                   ->setFirstname($faker->firstName())
                   ->setEmail('admin@admin.com')
                   ->setGender($faker->randomElement(['homme', 'femme', 'neutre']))
                   ->setLevel($faker->numberBetween(1,3))
                   ->setPhone('0123456789')
                   ->setPassword('admin')
                   ->setRoles(['ROLE_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-50 years', '-30 years'));
        
        $manager->persist($admin);
        
        // Creates 10 members
        $memberList = [];

        for ($i=1; $i < 11; $i++) { 
            $member = new User();
            $member->setLastname($faker->lastName())
                    ->setFirstname($faker->firstName())
                    ->setEmail('member'.$i.'@member.com')
                    ->setGender($faker->randomElement(['homme', 'femme', 'neutre']))
                    ->setLevel($faker->numberBetween(1,3))
                    ->setPhone('0123456789')
                    ->setPassword('member')
                    ->setRoles(['ROLE_MEMBER'])
                    ->setCreatedAt(new DateTimeImmutable('now'))
                    ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-15 years'));

            $memberList[] = $member;

            $manager->persist($member);
        }

         // Club
         $club = new Club();
         $club->setStartingTime(new Datetime('12:00'))
              ->setEndingTime(new DateTime('20:00'))
              ->setName('Passing ShO\'t')
              ->setDescription($faker->paragraph())
              ->setCreatedAt(new DateTimeImmutable())
              ->setUser($superAdmin);

        $manager->persist($club);

        // Courts
        $courtList = [];

        for ($i=1; $i <11 ; $i++) { 
            $court = new Court();
            $court->setName('Terrain'.$i)
                  ->setSurface($faker->numberBetween(1,2))
                  ->setLightning($faker->boolean())
                  ->setType($faker->boolean())
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailledMap('https://picsum.photos/200/300')
                  ->setCreatedAt(new DateTimeImmutable());

            $courtList[] = $court;
            
            $manager->persist($court);
        }

        // Reservation
        for ($i=1; $i < 21 ; $i++) { 
            $reservation = new Reservation();

            // Select reservation random date from today to + 1 week
            /**
             * @var DateTimeImmutable
             */
            $reservationDate = $faker->immutableDateTimeBetween('now', '+1 week');

            // set random start hour from 12 to 19
            $start_hour = mt_rand(12, 19);
            // Set end hour (start_hour + 1)
            $end_hour = $start_hour + 1;

            // Set Random Reservation StartDateTime
            $startDateTime = $reservationDate->setTime($start_hour, 0);
            // Set Reservation EndDateTime
            $endDateTime = $reservationDate->setTime($end_hour, 0);

            $reservation->setStartDatetime($startDateTime)
                        ->setEndDatetime($endDateTime)
                        ->setStatus(1)
                        ->setCountPlayers(2)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setCourt($faker->randomElement($courtList))
                        ->setUser($faker->randomElement($memberList));

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
