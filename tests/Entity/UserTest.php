<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private User $user;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    private function validateProperty(User $user, string $propertyName)
    {
        return $this->validator->validateProperty($user, $propertyName);
    }

    public function testGetId(): void
    {
        // Normalement, l'ID est null par défaut
        $this->assertNull($this->user->getId());
    }

    public function testUsername(): void
    {
        $username = 'TestUser';
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUsername());
    }

    public function testEmail(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    public function testRoles(): void
    {
        $roles = ['ROLE_ADMIN'];
        $this->user->setRoles($roles);

        // Assurez-vous que ROLE_USER est toujours inclus
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $this->user->getRoles());
    }

    public function testPassword(): void
    {
        $password = 'securepassword';
        $this->user->setPassword($password);
        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testUserIdentifier(): void
    {
        $username = 'IdentifierTest';
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUserIdentifier());
    }

    public function testValidUsername(): void
    {
        $user = new User();
        $user->setUsername('ValidUsername');
        $violations = $this->validateProperty($user, 'username');
        $this->assertCount(0, $violations);
    }

    public function testInvalidUsername(): void
    {
        $user = new User();
        $user->setUsername(''); // Blank username
        $violations = $this->validateProperty($user, 'username');
        $this->assertGreaterThan(0, $violations->count());
    }

    public function testValidEmail(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $violations = $this->validateProperty($user, 'email');
        $this->assertCount(0, $violations);
    }

    public function testInvalidEmail(): void
    {
        $user = new User();
        $user->setEmail('invalid-email'); // Invalid email format
        $violations = $this->validateProperty($user, 'email');
        $this->assertGreaterThan(0, $violations->count());
    }

    public function testPasswordConstraints(): void
    {
        $user = new User();
        $user->setPassword(''); // Blank password
        $violations = $this->validateProperty($user, 'password');
        $this->assertGreaterThan(0, $violations->count());
    }
}
