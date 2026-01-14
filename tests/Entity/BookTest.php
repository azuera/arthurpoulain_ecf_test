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

        // Test des getters/setters basiques
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

        // Un nouveau livre n'a pas d'ID (généré par la base de données)
        $this->assertNull($book->getId());

        // Les collections sont initialisées
        $this->assertCount(0, $book->getAuthors());
        $this->assertCount(0, $book->getComments());

        // Pas d'éditeur par défaut
        $this->assertNull($book->getEditor());
    }

    public function testAssociationWithAuthor(): void
    {
        $book = new Book();
        $author = new Author();

        // Associer l'auteur au livre
        $book->addAuthor($author);

        // Vérifier que l'auteur est bien associé
        $this->assertCount(1, $book->getAuthors());
        $this->assertTrue($book->getAuthors()->contains($author));

        // Vérifier que le livre est bien associé à l'auteur (relation bidirectionnelle)
        $this->assertTrue($author->getBooks()->contains($book));

        // Retirer l'association
        $book->removeAuthor($author);

        $this->assertCount(0, $book->getAuthors());
        $this->assertCount(0, $author->getBooks());
    }

    public function testAssociationWithEditor(): void
    {
        $book = new Book();
        $editor = new Editor();

        // Associer l'éditeur au livre
        $book->setEditor($editor);

        // Vérifier que l'éditeur est bien associé
        $this->assertSame($editor, $book->getEditor());

        // Vérifier que le livre est bien associé à l'éditeur (relation bidirectionnelle)
        $editor->addBook($book);
        $this->assertTrue($editor->getBooks()->contains($book));
    }
}