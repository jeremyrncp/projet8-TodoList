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
    const BASIC_USER = 'basic_user';
    const ADMIN_USER = 'admin_user';

    const USERNAME = 'martin';
    const ADMIN_USERNAME = 'admin';
    const CUD_USERNAME = 'durand';

    const PASSWORD = 'test';
    const EMAIL = 'martin@test.com';

    public function load(ObjectManager $manager)
    {
        $user = $this->getUser();
        $admin = $this->getAdmin();

        $this->addReference(self::BASIC_USER, $user);
        $this->addReference(self::ADMIN_USER, $admin);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($this->getCudUser());

        $manager->flush();
    }

    private function getCudUser(): User
    {
        $user = new User();
        $user->setUsername(self::CUD_USERNAME);
        $user->setEmail('cud' . self::EMAIL);

        $user->setPassword(password_hash(self::PASSWORD, PASSWORD_BCRYPT));

        return $user;
    }

    private function getUser(): User
    {
        $user = new User();
        $user->setUsername(self::USERNAME);
        $user->setEmail(self::EMAIL);

        $user->setPassword(password_hash(self::PASSWORD, PASSWORD_BCRYPT));

        return $user;
    }

    private function getAdmin(): User
    {
        $user = new User();
        $user->setUsername(self::ADMIN_USERNAME);
        $user->setEmail('admin' . self::EMAIL);
        $user->setRoles(
            ['ROLE_ADMIN']
        );

        $user->setPassword(password_hash(self::PASSWORD, PASSWORD_BCRYPT));

        return $user;
    }
}