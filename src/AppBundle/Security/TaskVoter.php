<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const TOGGLE = 'toggle';
    const DELETE = 'delete';
    const EDIT = 'edit';
    const LIST_ACTION = [self::DELETE, self::EDIT];

    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Task $subject */
        if ($token->getUser()->hasRole('ROLE_ADMIN')) {
            return $this->voteForAdmin($subject, $token->getUser());
        } else {
            return $subject->getUser() === $token->getUser();
        }
    }

    private function voteForAdmin(Task $task, User $user): bool
    {
        if ($task->getUser() === null || $task->getUser()->getId() === $user->getId()) {
            return true;
        }

        return false;
    }

    public function supports($attribute, $subject)
    {
        if (!$subject instanceof Task) {
            return false;
        }

        if (!\in_array($attribute, self::LIST_ACTION)) {
            return false;
        }

        return true;
    }
}