<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testAdminRedirectsToLoginWhenNotAuthenticated(): void
    {
        $client = static::createClient();

        // Tentative d'accès à une page admin sans être connecté
        $client->request('GET', '/admin/book');

        // Doit rediriger vers la page de login
        $this->assertResponseRedirects('/login');
    }

    public function testAdminAccessWithAdminUser(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Vérifie d'abord si l'utilisateur admin existe déjà
        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => 'admin@biblios.test']);

        if ($existingUser) {
            // Utilise l'utilisateur existant
            $testUser = $existingUser;
        } else {
            // Crée un nouvel utilisateur admin seulement s'il n'existe pas
            $testUser = new User();
            $testUser->setEmail('admin@biblios.test');
            $testUser->setFirstname('Admin');
            $testUser->setLastname('Test');
            $testUser->setPassword('$2y$13$TestPasswordHash');
            $testUser->setUsername('admin_test');
            $testUser->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($testUser);
            $entityManager->flush();
        }

        // Connecte l'utilisateur
        $client->loginUser($testUser);

        // Accès à une page admin avec l'utilisateur admin
        $client->request('GET', '/admin/book');

        // Doit retourner 200 (accès autorisé)
        $this->assertResponseStatusCodeSame(200);
    }
}