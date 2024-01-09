<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserRepositoryTest extends KernelTestCase
{
    use ResetDatabase;

    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NotSupported
     */
    public function testCreateAndFindUser(): void
    {
        // Créer et persister un nouvel utilisateur
        $user = new User();
        $user->setUsername('TestUser');
        $user->setEmail('test@example.com');
        $user->setPassword('testpassword');
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Rechercher l'utilisateur dans la base de données
        $userRepository = $this->entityManager->getRepository(User::class);
        $foundUser = $userRepository->findOneBy(['username' => 'TestUser']);

        // Assertions
        $this->assertNotNull($foundUser);
        $this->assertEquals('TestUser', $foundUser->getUsername());

        // Nettoyage (optionnel)
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Fermeture du manager si nécessaire
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
