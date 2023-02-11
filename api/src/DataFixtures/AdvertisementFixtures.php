<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AdvertisementFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $object = (new Advertisement())
                ->setName($faker->sentence(3))
                ->setDescription($faker->sentence(10))
                ->setOwner($this->getReference($faker->numberBetween(4, 6)))
                ->setType($faker->sentence(1))
                ->setDateStart($faker->dateTimeBetween('-6 months'))
                ->setDateEnd($faker->dateTimeBetween('-5 months'))
                ->setCity($faker->numberBetween(0, 4));

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            CityFixtures::class,
        ];
    }
}