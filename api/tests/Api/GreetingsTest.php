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

        static::createClient()->request('POST', '/api/login', ['json' => [
            'email' => 'rexrider75@gmail.com',
            'password' => 'testtest',
        ]]);

        $this->assertResponseStatusCodeSame(200);
//        $this->assertJsonContains([
//            '@context' => '/contexts/Greeting',
//            '@type' => 'Greeting',
//            'name' => 'Kévin',
//        ]);
    }
}
