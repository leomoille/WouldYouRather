<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class RegistrationControllerTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testRegistration(): void
    {
        $uniqueUsername = 'TestUser' . rand(1, 5);
        $uniqueEmail    = 'test' . rand(1, 5) . '@example.com';

        $this->browser()
            ->visit('/register')
            ->fillField('registration_form[username]', $uniqueUsername)
            ->fillField('registration_form[email]', $uniqueEmail)
            ->fillField('registration_form[plainPassword]', 'password123')
            ->click('S\'inscrire')
            ->interceptRedirects()
            ->followRedirects()
            ->assertOn('/question/play')
            ->assertSuccessful();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user           = $userRepository->findBy(['email' => $uniqueEmail]);
        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été créé.');
    }
}
