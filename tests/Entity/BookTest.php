<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use App\Enum\BookStatus;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testGettersSetters(): void
    {
        $book = new Book();

        $book->setTitle('Le Petit Prince');
        $book->setIsbn('9782070612758');
        $book->setCover('https://example.com/cover.jpg');
        $book->setEditedAt(new \DateTimeImmutable('2023-01-01'));
        $book->setPlot('Un conte poétique et philosophique...');
        $book->setPageNumber(96);
        $book->setStatus(BookStatus::Available);

        $this->assertEquals('Le Petit Prince', $book->getTitle());
        $this->assertEquals('9782070612758', $book->getIsbn());
        $this->assertEquals('https://example.com/cover.jpg', $book->getCover());
        $this->assertEquals('Un conte poétique et philosophique...', $book->getPlot());
        $this->assertEquals(96, $book->getPageNumber());
        $this->assertEquals(BookStatus::Available, $book->getStatus());
        $this->assertInstanceOf(\DateTimeImmutable::class, $book->getEditedAt());
    }

    public function testDefaultValues(): void
    {
        $book = new Book();

        $this->assertNull($book->getId());

        $this->assertCount(0, $book->getAuthors());
        $this->assertCount(0, $book->getComments());

        $this->assertNull($book->getEditor());
    }

    public function testAssociationWithAuthor(): void
    {
        $book = new Book();
        $author = new Author();

        $book->addAuthor($author);

        $this->assertCount(1, $book->getAuthors());
        $this->assertTrue($book->getAuthors()->contains($author));

        $this->assertTrue($author->getBooks()->contains($book));

        $book->removeAuthor($author);

        $this->assertCount(0, $book->getAuthors());
        $this->assertCount(0, $author->getBooks());
    }

    public function testAssociationWithEditor(): void
    {
        $book = new Book();
        $editor = new Editor();

        $book->setEditor($editor);

        $this->assertSame($editor, $book->getEditor());

        $editor->addBook($book);
        $this->assertTrue($editor->getBooks()->contains($book));
    }
}