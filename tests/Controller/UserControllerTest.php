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

    public function testCreateAdminUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'admin-'.mt_rand(),
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email'.mt_rand().'@mail.com',
        ]);
        $form['user[roles]']->tick();

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testCreateRegularUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user-'.mt_rand(),
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email'.mt_rand().'@mail.com',
            'user[roles]' => false,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditUserBecomeAdmin()
    {
        $newUsername = 'newname'.mt_rand();

        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/28/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => $newUsername,
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $form['user[roles]']->tick();

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditAdminBecomeRegular()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'admin',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/users/28/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[roles]' => false,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}
