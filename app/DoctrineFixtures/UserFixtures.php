<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */
namespace Application\DoctrineFixtures;

use \Doctrine\Bundle\FixturesBundle\Fixture;
use \Doctrine\Common\Persistence\ObjectManager;
use \AppBundle\Entity\User;

class UserFixtures extends Fixture
{
    const USERNAME = 'martin';
    const PASSWORD = 'test';
    const EMAIL = 'martin@test.com';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername(self::USERNAME);
        $user->setEmail(self::EMAIL);

        $user->setPassword(password_hash(self::PASSWORD, PASSWORD_BCRYPT));

        $manager->persist($user);
        $manager->flush();
    }
}