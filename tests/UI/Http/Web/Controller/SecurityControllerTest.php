<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SecurityControllerTest extends WebTestCase
{
    const ERROR_AUTENTICACION = "Se ha producido un error de autenticación";
    /**
     * @test
     *
     * @group e2e
     */
    public function sign_in_after_create_user(): void
    {
        $this->createUser('abelardolg@gmail.com');

        self::ensureKernelShutdown();
        $client = self::createClient();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('abelardolg@gmail.com');
        $form->get('_password')->setValue('crqs-demo');

        $client->submit($form);

        $crawler = $client->followRedirect();

        self::assertSame('/profile', \parse_url($crawler->getUri(), \PHP_URL_PATH));
        self::assertSame(1, $crawler->filter('html:contains("Hi abelardolg@gmail.com")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function logout_should_remove_session_and_profile_redirect_sign_in(): void
    {
        $this->createUser('abelardolg@gmail.com');

        self::ensureKernelShutdown();
        $client = self::createClient();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('abelardolg@gmail.com');
        $form->get('_password')->setValue('crqs-demo');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame('/profile', \parse_url($crawler->getUri(), \PHP_URL_PATH));

        $client->click($crawler->selectLink('Exit')->link());

        $crawler = $client->followRedirect();
        self::assertSame('/', \parse_url($crawler->getUri(), \PHP_URL_PATH));

        $client->request('GET', '/profile');

        $crawler = $client->followRedirect();
        self::assertSame('/sign-in', \parse_url($crawler->getUri(), \PHP_URL_PATH));
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function login_should_display_an_error_when_bad_credentials(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('an@email.com');
        $form->get('_password')->setValue('password-so-safe');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("Se ha producido un error de autenticación.")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function login_should_display_an_error_when_bad_invalid_email(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('an@email');
        $form->get('_password')->setValue('password-so-safe');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("Se ha producido un error de autenticación.")')->count());
    }

    private function createUser(string $email, string $password = 'crqs-demo'): Crawler
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $crawler = $client->request('GET', '/sign-up');

        $form = $crawler->selectButton('Send')->form();

        $form->get('email')->setValue($email);
        $form->get('password')->setValue($password);

        $crawler = $client->submit($form);

        return $crawler;
    }
}
