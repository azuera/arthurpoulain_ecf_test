<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use App\Entity\User;
use App\Enum\BookStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Créer un utilisateur ADMIN d'abord
        $adminUser = new User();
        $adminUser->setEmail('admin@biblios.test');
        $adminUser->setFirstname('Admin');
        $adminUser->setLastname('Test');
        $adminUser->setUsername('admin_test');
        $adminUser->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $adminUser,
            'admin123'
        );
        $adminUser->setPassword($hashedPassword);
        $manager->persist($adminUser);

        // 2. Créer un utilisateur normal pour created_by
        $normalUser = new User();
        $normalUser->setEmail('user@biblios.test');
        $normalUser->setFirstname('Normal');
        $normalUser->setLastname('User');
        $normalUser->setUsername('normal_user');
        $normalUser->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $normalUser,
            'user123'
        );
        $normalUser->setPassword($hashedPassword);
        $manager->persist($normalUser);

        // 3. Créer un éditeur
        $editor = new Editor();
        $editor->setName('Gallimard');
        $manager->persist($editor);

        // 4. Créer un auteur
        $author = new Author();
        $author->setName('Antoine de Saint-Exupéry');
        $author->setDateOfBirth(new \DateTimeImmutable('1900-06-29'));
        $author->setNationality('Française');
        $manager->persist($author);

        // 5. Créer un livre avec created_by
        $book = new Book();
        $book->setTitle('Le Petit Prince');
        $book->setIsbn('9782070612758');
        $book->setCover('https://example.com/cover.jpg');
        $book->setEditedAt(new \DateTimeImmutable('1943-01-01'));
        $book->setPlot('Un conte poétique et philosophique...');
        $book->setPageNumber(96);
        $book->setStatus(BookStatus::Available);
        $book->setEditor($editor);
        $book->addAuthor($author);
        $book->setCreatedBy($normalUser); // ← IMPORTANT : assigner un utilisateur
        $manager->persist($book);

        $manager->flush();
    }
}