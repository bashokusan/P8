<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage(){
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testLogout(){
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/logout');
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}