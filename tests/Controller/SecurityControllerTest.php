<?php

namespace App\Tests\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class SecurityControllerTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testLogin(): void
    {
        $user = UserFactory::createOne([
            'username' => 'pseudoo',
            'password' => 'password',
        ]);

        $this->browser()
            ->visit('/login')
            ->fillField('username', $user->getUsername())
            ->fillField('password', 'password')
            ->click('Se connecter')
            ->interceptRedirects()
            ->followRedirects()
            ->assertOn('/question/play')
            ->assertSuccessful();
    }
}
