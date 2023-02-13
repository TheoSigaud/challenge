<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        $pwd = '$2y$13$SycYF7SwGuxS/x6OsvcepO3Ly/jX.di80ugEQ.JG1UTXlguaaayGK';

        $object = (new User())
            ->setEmail("aa@aa.aa")
            ->setRoles(['ROLE_USER'])
            ->setPassword($pwd)
            ->setToken(bin2hex(random_bytes(32)))
            ->setAddress("4 rue de la paix")
            ->setBirthday(new \DateTime("1990-01-01"))
            ->setLastname("sidox")
            ->setFirstname("sidox");

            $object_2 = (new User())
            ->setEmail("rexrider75@gmail.com")
            ->setRoles(['ROLE_USER'])
            ->setPassword("testtest")
            ->setToken(bin2hex(random_bytes(32)))
            ->setAddress("4 rue de la paix")
            ->setBirthday(new \DateTime("1990-01-01"))
            ->setLastname("sidox")
            ->setFirstname("sidox");


        $admin = (new User())
            ->setEmail("admin@aa.aa")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
            ->setPassword($pwd)
            ->setToken(bin2hex(random_bytes(32)))
            ->setAddress("4 rue de la paix")
            ->setBirthday(new \DateTime("1990-01-01"))
            ->setLastname("sidox")
            ->setFirstname("sidox");

        $modo = (new User())
            ->setEmail("modo@aa.aa")
            ->setRoles(['ROLE_MODERATOR'])
            ->setPassword($pwd)
            ->setPassword($pwd)
            ->setToken(bin2hex(random_bytes(32)))
            ->setAddress("4 rue de la paix")
            ->setBirthday(new \DateTime("1990-01-01"))
            ->setLastname("sidox")
            ->setFirstname("sidox");



        $manager->persist($object);
        $manager->persist($object_2);
        $manager->persist($modo);
        $manager->persist($admin);

        $manager->flush();
    }
}
