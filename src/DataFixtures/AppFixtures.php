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
use App\Entity\Gender;
use App\Entity\Surface;
use Faker\Core\File;
use Proxies\__CG__\App\Entity\Surface as EntitySurface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Connection from database
     */
    private $connection;
    private $passwordHasher;

    public function __construct(Connection $connection, UserPasswordHasherInterface $passwordHasher)
    {
        // Get connexion from database (DBAL ~= PDO)
        $this->connection = $connection;
        $this->passwordHasher = $passwordHasher;
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
        $this->connection->executeQuery('TRUNCATE TABLE surface');
        $this->connection->executeQuery('TRUNCATE TABLE gender');
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

        // Gender
        $male = new Gender();
        $male->setType('Homme')
             ->setCreatedAt(new DateTimeImmutable());
        
        $manager->persist($male);

        $female = new Gender();
        $female->setType('Femme')
             ->setCreatedAt(new DateTimeImmutable());
        
        $manager->persist($female);

        $neutral = new Gender();
        $neutral->setType('Neutre')
             ->setCreatedAt(new DateTimeImmutable());
        
        $manager->persist($neutral);


        //User
        // Creates super_admin
        // Julien
        $superAdmin1 = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin1, 'superadmin');
        $superAdmin1->setLastname('Dabadie')
                   ->setFirstname('Julien')
                   ->setEmail('julien@passingshot.com')
                   ->setGender($male)
                   ->setLevel('Confirmé')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin1);

        // Vincent
        $superAdmin2 = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin2, 'superadmin');
        $superAdmin2->setLastname('Mitry')
                   ->setFirstname('Vincent')
                   ->setEmail('vincent@passingshot.com')
                   ->setGender($male)
                   ->setLevel('Intermédiaire')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin2);

        // Simon
        $superAdmin3 = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin3, 'superadmin');
        $superAdmin3->setLastname('Daussy')
                   ->setFirstname('Simon')
                   ->setEmail('simon@passingshot.com')
                   ->setGender($male)
                   ->setLevel('Débutant')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin3);

        // Jérémy
        $superAdmin4 = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin4, 'superadmin');
        $superAdmin4->setLastname('Poinas')
                   ->setFirstname('Jérémy')
                   ->setEmail('jeremy@passingshot.com')
                   ->setGender($male)
                   ->setLevel('Débutant')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin4);

        // Yannis
        $superAdmin5 = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin5, 'superadmin');
        $superAdmin5->setLastname('Thalgott')
                   ->setFirstname('Yannis')
                   ->setEmail('yannis@passingshot.com')
                   ->setGender($male)
                   ->setLevel('Débutant')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin5);

        // Creates admin 
        $admin = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setLastname('Paul')
                   ->setFirstname('Sean')
                   ->setEmail('admin@admin.com')
                   ->setGender($male)
                   ->setLevel('Confirmé')
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-50 years', '-30 years'));
        
        $manager->persist($admin);
        
        // Creates 10 members
        $memberList = [];

        for ($i=1; $i < 11; $i++) { 
            $member = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($member, 'member');
            $member->setLastname($faker->lastName())
                    ->setFirstname($faker->firstName())
                    ->setEmail('member'.$i.'@member.com')
                    ->setGender($faker->randomElement([$male, $female, $neutral]))
                    ->setLevel($faker->randomElement(['Débutant', 'Intermédiaire', 'Confirmé']))
                    ->setPhone('0123456789')
                    ->setPassword($hashedPassword)
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
              ->setUser($superAdmin1);

        $manager->persist($club);

        // Surface 
        $clay = new Surface();
        $clay->setName('Terre battue')
             ->setCreatedAt(new DateTimeImmutable());

        $manager->persist($clay);

        $greenset = new Surface();
        $greenset->setName('Greenset')
                 ->setCreatedAt(new DateTimeImmutable());
                 
         $manager->persist($greenset);

        // Courts
        $courtList = [];

        for ($i=1; $i <12 ; $i++) { 
            $court = new Court();
            $court->setName('Terrain '.$i)
                  ->setSurface($faker->randomElement([$clay, $greenset]))
                  ->setLightning($faker->boolean())
                  ->setIndoor($faker->boolean())
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('terrain'.$i)
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
                        ->setStatus(true)
                        ->setCountPlayers(2)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setCourt($faker->randomElement($courtList))
                        ->setUser($faker->randomElement($memberList));

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
