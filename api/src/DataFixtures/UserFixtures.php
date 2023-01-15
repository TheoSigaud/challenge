<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Joke;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
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
                ->setPassword($pwd);

        $admin = (new User())
            ->setEmail("admin@aa.aa")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd);

        $modo= (new User())
            ->setEmail("modo@aa.aa")
            ->setRoles(['ROLE_MODERATOR'])
            ->setPassword($pwd);



        $manager->persist($object);
        $manager->persist($modo);
        $manager->persist($admin);

        $manager->flush();
    }

}
