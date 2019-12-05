<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the controllers defined inside DefaultController
 */
class DefaultControllerTest extends WebTestCase
{
    public function testHomepageNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame(
            'http://localhost/login',
            $response->getTargetUrl()
        );
    }

    public function testHomepageLoggedIn()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
