<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 25/01/19
 * Time: 21:50
 */

namespace Tests\Functionnal\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Application\DoctrineFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
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

    public function testMustObtainAnErrorWhenCreateAnUserButIsntAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::CUD_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $client->request('GET', '/users/create');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenEditAnUserButIsntAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::CUD_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $client->request('GET', '/users/' . $this->getUser()->getId() . '/edit');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenGetUsersButIsntAnadministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::CUD_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $client->request('GET', '/users');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainASuccessWhenCreateAnUserAsAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues([
            'user[username]' => 'MartinDurand',
            'user[password][first]' => 'test',
            'user[password][second]' => 'test',
            'user[email]' => 'martin.durand@gmail.com'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('L\'utilisateur a bien été ajouté.', $crawler->filter('.alert-success')->first()->html());
    }

    public function testMustObtainASuccessWhenEditAnUserAsAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $idUser = $this->getUser()->getId();

        $crawler = $client->request('GET', '/users/' . $idUser. '/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('L\'utilisateur a bien été modifié', $crawler->filter('.alert-success')->first()->html());
    }

    public function testMustObtainASuccessWhenGetUsersAsAnAdministrator()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixtures::ADMIN_USERNAME,
            'PHP_AUTH_PW'   => UserFixtures::PASSWORD,
        ]);

        $client->request('GET', '/users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    private function getUser(): User
    {
        return $this->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(
                        ['username' => UserFixtures::CUD_USERNAME]
                    );
    }
}
