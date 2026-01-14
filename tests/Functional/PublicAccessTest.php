<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicAccessTest extends WebTestCase
{
    public function testPublicPageReturns200(): void
    {
        $client = static::createClient();

        // Test d'accès à la liste des livres (route publique)
        $client->request('GET', '/book');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testHomePageReturns200(): void
    {
        $client = static::createClient();

        // Test d'accès à la page d'accueil
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);
    }
}