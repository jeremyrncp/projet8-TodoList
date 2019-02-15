<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Form\Handler\Task;

use AppBundle\Form\Handler\Handler;
use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\Form\FormInterface;
use AppBundle\Exception\InvalidTypeException;

class TaskHandler extends Handler
{
    /**
     * @param array $options
     * @throws InvalidTypeException
     */
    protected function isValidOptions(array $options)
    {
        if (!$options['entity'] instanceof Task) {
            throw new InvalidTypeException('Entity must be an ' . Task::class);
        }
    }

    /**
     * @param FormInterface $form
     * @throws InvalidTypeException
     */
    protected function isValidForm(FormInterface $form)
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof TaskType) {
            throw new InvalidTypeException('Task form must be an ' . TaskType::class . ', instance of ' . get_class($form) . 'given');
        }
    }
}
