<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace Tests\Functionnal\AppBundle\Controller;

use Application\DoctrineFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTaskControllerTest extends WebTestCase
{
    public function testMustCreateATaskWithAnIdentifiedUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::CUD_USERNAME,
            'PHP_AUTH_PW'   => 'test',
        ]);

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues([
            'task[title]' => 'Task title',
            'task[content]' => 'MigrationsTest description'
        ]);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('La tâche a bien été ajoutée.', $crawler->filter('.alert-success')->first()->html());
    }
}
