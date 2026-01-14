<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function testGettersSetters(): void
    {
        $author = new Author();

        $author->setName('Antoine de Saint-Exupéry');
        $author->setDateOfBirth(new \DateTimeImmutable('1900-06-29'));
        $author->setDateOfDeath(new \DateTimeImmutable('1944-07-31'));
        $author->setNationality('Française');

        $this->assertEquals('Antoine de Saint-Exupéry', $author->getName());
        $this->assertEquals('Française', $author->getNationality());
        $this->assertInstanceOf(\DateTimeImmutable::class, $author->getDateOfBirth());
        $this->assertInstanceOf(\DateTimeImmutable::class, $author->getDateOfDeath());
    }

    public function testDefaultValues(): void
    {
        $author = new Author();

        $this->assertNull($author->getId());
        $this->assertCount(0, $author->getBooks());
        $this->assertNull($author->getDateOfDeath());
        $this->assertNull($author->getNationality());
    }

    public function testAssociationWithBook(): void
    {
        $author = new Author();
        $book = new Book();


        $author->addBook($book);


        $this->assertCount(1, $author->getBooks());
        $this->assertTrue($author->getBooks()->contains($book));

        $this->assertCount(0, $book->getAuthors());
    }
}