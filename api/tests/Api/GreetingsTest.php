<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class GreetingsTest extends ApiTestCase
{
    public function testCreateGreeting(): void
    {
//        static::createClient()->request('POST', '/greetings', ['json' => [
//            'name' => 'Kévin',
//        ]]);

// $object_2 = (new User())
//             ->setEmail("rexrider75@gmail.com")
//             ->setRoles(['ROLE_USER'])
//             ->setPassword("testtest")
//             ->setToken(bin2hex(random_bytes(32)))
//             ->setAddress("4 rue de la paix")
//             ->setBirthday(new \DateTime("1990-01-01"))
//             ->setLastname("sidox")
//             ->setFirstname("sidox");

        static::createClient()->request('POST', '/api/login', ['json' => [
            'email' => 'rexrider75@gmail.com',
            'password' => 'testtest',
            'address' => '4 rue de la paix',
            'birthday' => '1990-01-01',
            'lastname' => 'sidox',
            'firstname' => 'sidox',
            'token' => bin2hex(random_bytes(32)),
            'roles' => ['ROLE_USER'],
        ]]);

        $this->assertResponseStatusCodeSame(200);
//        $this->assertJsonContains([
//            '@context' => '/contexts/Greeting',
//            '@type' => 'Greeting',
//            'name' => 'Kévin',
//        ]);
    }
}
