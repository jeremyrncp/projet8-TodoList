<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 14/02/19
 * Time: 19:53
 */

namespace AppBundle\Form\Handler\User;

use AppBundle\Form\Handler\Handler;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\FormInterface;
use AppBundle\Exception\InvalidTypeException;


class UserHandler extends Handler
{
    /**
     * @param array $options
     * @throws InvalidTypeException
     */
    protected function isValidOptions(array $options)
    {
        if (!$options['entity'] instanceof User) {
            throw new InvalidTypeException('Entity must be an ' . User::class);
        }
    }

    /**
     * @param FormInterface $form
     * @throws InvalidTypeException
     */
    protected function isValidForm(FormInterface $form)
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof UserType) {
            throw new InvalidTypeException('User form must be an ' . UserType::class . ', instance of ' . get_class($form) . 'given');
        }
    }
}