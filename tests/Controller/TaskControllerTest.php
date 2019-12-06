<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the controllers defined inside TaskController
 */
class TaskControllerTest extends WebTestCase
{
    public function testTasksPage()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/tasks');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateTask()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'title',
            'task[content]' => 'content',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testShowTask()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/tasks/title');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $newTaskTitle = 'new title';

        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/tasks/1/edit');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => $newTaskTitle,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testToggleTask()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/tasks/1/toggle');

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testDeleteTaskUnauthorized()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'newname8642626',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/tasks/1/delete');

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskAuthorized()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER'  => 'user',
            'PHP_AUTH_PW' => 'password',
        ]);
        $client->request('GET', '/tasks/1/delete');

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}
