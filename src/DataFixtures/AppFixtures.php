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
        $superAdmin = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($superAdmin, 'superadmin');
        $superAdmin->setLastname($faker->lastName())
                   ->setFirstname($faker->firstName())
                   ->setEmail('superadmin@superadmin.com')
                   ->setGender($faker->randomElement([$male, $female, $neutral]))
                   ->setLevel($faker->randomElement(['Débutant', 'Intermédiaire', 'Confirmé']))
                   ->setPhone('0123456789')
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_SUPER_ADMIN'])
                   ->setCreatedAt(new DateTimeImmutable('now'))
                   ->setBirthdate($faker->immutableDateTimeBetween('-60 years', '-30 years'));
        
        $manager->persist($superAdmin);

        // Creates admin 
        $admin = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setLastname($faker->lastName())
                   ->setFirstname($faker->firstName())
                   ->setEmail('admin@admin.com')
                   ->setGender($faker->randomElement([$male, $female, $neutral]))
                   ->setLevel($faker->randomElement(['Débutant', 'Intermédiaire', 'Confirmé']))
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
              ->setUser($superAdmin);

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

        //Court 1
        $court1 = new Court();
        $court1->setName('Arthur Ashe')
                  ->setSurface($clay)
                  ->setLightning(true)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('arthur-ashe')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court1;
        $manager->persist($court1);

        //Court 2
        $court2 = new Court();
        $court2->setName('Ken Rosewall')
                  ->setSurface($clay)
                  ->setLightning(true)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('ken-rosewall')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court2;
        $manager->persist($court2);

        //Court 3
        $court3 = new Court();
        $court3->setName('River Oaks')
                  ->setSurface($greenset)
                  ->setLightning(true)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('river-oaks')
                  ->setCreatedAt(new DateTimeImmutable());

        $courtList[] = $court3;
        $manager->persist($court3);

        //Court 4
        $court4 = new Court();
        $court4->setName('Olimpiisky')
                  ->setSurface($greenset)
                  ->setLightning(true)
                  ->setIndoor(true)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('olimpiisky')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court4;
        $manager->persist($court4);

        //Court 5
        $court5 = new Court();
        $court5->setName('Suzanne Lenglen')
                  ->setSurface($clay)
                  ->setLightning(true)
                  ->setIndoor(true)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('suzanne-lenglen')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court5;                  
        $manager->persist($court5);

        //Court 6
        $court6 = new Court();
        $court6->setName('Weissenhof')
                  ->setSurface($greenset)
                  ->setLightning(false)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('weissenhof')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court6;
        $manager->persist($court6);

        //Court 7
        $court7 = new Court();
        $court7->setName('Rod Laver')
                  ->setSurface($greenset)
                  ->setLightning(false)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('rod-laver')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court7;
        $manager->persist($court7);

        //Court 8
        $court8 = new Court();
        $court8->setName('Philippe Chatrier')
                  ->setSurface($clay)
                  ->setLightning(false)
                  ->setIndoor(false)
                  ->setStartTime(new DateTime('12:00'))
                  ->setEndTime(new DateTime('20:00'))
                  ->setClub($club)
                  ->setPicture('https://picsum.photos/200/300')
                  ->setDetailedMap('https://picsum.photos/200/300')
                  ->setSlug('philippe-chatrier')
                  ->setCreatedAt(new DateTimeImmutable());
        
        $courtList[] = $court8;
        $manager->persist($court8);

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
