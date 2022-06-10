<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Court;
use App\Entity\User;
use App\Faker\Provider\DateTimeImmutableFaker;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Validator\Constraints\Time;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new DateTimeImmutableFaker($faker));

        // Users
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
            
            $manager->persist($court);
        }

        $manager->flush();
    }
}
