<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FixturesTest extends WebTestCase
{
    public function testFixturesAreLoaded(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $books = $entityManager->getRepository(Book::class)->findAll();
        $this->assertNotEmpty($books, 'Aucun livre trouvé dans la base de test');

        $authors = $entityManager->getRepository(Author::class)->findAll();
        $this->assertNotEmpty($authors, 'Aucun auteur trouvé dans la base de test');

        $editors = $entityManager->getRepository(Editor::class)->findAll();
        $this->assertNotEmpty($editors, 'Aucun éditeur trouvé dans la base de test');
    }
}