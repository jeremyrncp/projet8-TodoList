<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findUserAndAnonymousTask(User $user)
    {
        return $this->createQueryBuilder('t')
                   ->where('t.user = :user')
                   ->orWhere('t.user IS NULL')
                   ->orderBy('t.createdAt', 'DESC')
                   ->setParameter('user', $user)
                   ->getQuery()
                   ->getResult()
        ;
    }
}
