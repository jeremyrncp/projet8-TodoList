<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Form\Handler\Task;

use AppBundle\Entity\Task;
use AppBundle\Form\Handler\FormHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CreateTaskHandler extends TaskHandler implements FormHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateTaskHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @param SessionInterface $session
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->session = $session;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     *
     * @throws \AppBundle\Exception\InvalidTypeException
     * @throws \AppBundle\Exception\MissingNecessaryOperation
     */
    public function handle(FormInterface $form): bool
    {
        $this->isValidForm($form);

        $options = $this->getOptions();
        $this->isValidOptions($options);

        /** @var $task Task */
        $task = $options['entity'];

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->security->getToken()->getUser());

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->session->getFlashBag()->add('success', 'La tâche a bien été ajoutée.');

            return true;
        }

        return false;
    }
}
