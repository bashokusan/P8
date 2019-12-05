<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the controllers defined inside UserController
 */
class UserControllerTest extends WebTestCase
{
    public function testAccessDeniedForRegularUsers()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);

        $client->request('GET', '/users');
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testUsersPageForAdmins()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);

        $client->request('GET', '/users');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'username',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email@mail.com',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditUser()
    {
        $newUsername = 'newname';

        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/1/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => $newUsername,
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}
