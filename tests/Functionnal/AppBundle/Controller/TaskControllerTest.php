<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace Tests\Functionnal\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Application\DoctrineFixtures\TaskFixtures;
use Application\DoctrineFixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    const ACTIONS = ['edit', 'toggle'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel  ->getContainer()
                                        ->get('doctrine')
                                        ->getManager();
    }

    public function testMustObtainASuccessWhenEditAndToggleAnAnonymousTaskAsAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $idAnonymousTask = $this->getAnonimousTask()->getId();

        foreach (self::ACTIONS as $action) {
            $crawler = $client->request('GET', '/tasks/' . $idAnonymousTask. '/' . $action);

            if ($action === 'toggle') {
                $this->assertEquals(302, $client->getResponse()->getStatusCode());
            } else {
                $this->assertEquals(200, $client->getResponse()->getStatusCode());

                $form = $crawler->selectButton('Modifier')->form();

                $client->submit($form);
                $crawler = $client->followRedirect();

                $this->assertContains('La tâche a bien été modifiée.', $crawler->filter('.alert-success')->first()->html());
            }
        }
    }


    public function testMustObtainAnErrorWhenIamAnAdminAndToggleOrEditTaskButTaskIfNotMyPropertyAndIsntAnonymous()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $task = $this->getTaskPropertyOfUser();

        foreach (self::ACTIONS as $action) {
            $client->request('GET', '/tasks/' . $task->getId() . '/' . $action);
            $this->assertEquals(403, $client->getResponse()->getStatusCode());
        }
    }

    public function testMustObtainAnErrorWhenDeleteOrEditTaskButTaskIfNotMyProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::CUD_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $task = $this->getTaskPropertyOfUser();

        foreach (self::ACTIONS as $action) {
            $client->request('GET', '/tasks/' . $task->getId() . '/' . $action);
            $this->assertEquals(403, $client->getResponse()->getStatusCode());
        }
    }

    private function getAnonimousTask(): Task
    {
        return $this->entityManager->getRepository(Task::class)->findOneBy(
            ['user' => null]
        );
    }

    private function getTaskPropertyOfUser(): Task
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(
            ['username' => UserFixtures::USERNAME]
        );

        return $this->entityManager->getRepository(Task::class)->findOneBy(
            ['user' => $user]
        );
    }

    public function testMustHaveFifteenTaskWhenIAmAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(
            (TaskFixtures::NUMBER_ADMIN_TASKS + TaskFixtures::NUMBER_ANONYMOUS_TASKS),
            $crawler->filter('.container .row .thumbnail')->getIterator()
        );
    }
    public function testMustHaveTenTaskWhenIAmAnUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(
            TaskFixtures::NUMBER_USER_TASKS,
            $crawler->filter('.container .row .thumbnail')->getIterator()
        );
    }
}
