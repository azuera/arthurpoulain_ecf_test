<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testAdminRedirectsToLoginWhenNotAuthenticated(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/book');


        $this->assertResponseRedirects('/login');
    }

    public function testAdminAccessWithAdminUser(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => 'admin@biblios.test']);

        if ($existingUser) {

            $testUser = $existingUser;
        } else {
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


        $client->loginUser($testUser);

        $client->request('GET', '/admin/book');

        $this->assertResponseStatusCodeSame(200);
    }
}