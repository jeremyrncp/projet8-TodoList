<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace Application\DoctrineFixtures;

use AppBundle\Entity\Task;
use \Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    const NUMBER_ADMIN_TASKS = 10;
    const NUMBER_ANONYMOUS_TASKS = 5;
    const NUMBER_USER_TASKS = 10;

    public function load(ObjectManager $manager)
    {
        $this->createAdminAndAnonymousTask($manager);
        $this->createUserTaks($manager);
        $manager->flush();
    }

    private function createUserTaks(ObjectManager $manager)
    {
        for ($i = 0 ; $i < 10 ; $i ++) {
            $task = new Task();
            $task->setUser($this->getReference(UserFixtures::BASIC_USER));
            $task->setContent('Task content');
            $task->setTitle('Task title');

            $manager->persist($task);
        }
    }
    private function createAdminAndAnonymousTask(ObjectManager $manager)
    {
        for ($i = 0 ; $i < 10 ; $i ++) {
            $task = new Task();
            $task->setUser($this->getReference(UserFixtures::ADMIN_USER));
            $task->setContent('Task content');
            $task->setTitle('Task title');

            $manager->persist($task);
        }

        for ($i = 0 ; $i < 5 ; $i ++) {
            $task = new Task();
            $task->setContent('Task anonymous content');
            $task->setTitle('Task anonymous title');

            $manager->persist($task);
        }
    }
}
