<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 14/02/19
 * Time: 19:54
 */

namespace AppBundle\Form\Handler\User;


use AppBundle\Entity\User;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Form\Handler\Handler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CreateUserHandler extends Handler implements FormHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateTaskHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param SessionInterface $session
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }


    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $options = $this->getOptions();
            /** @var User $user */
            $user = $options['entity'];

            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->session->getFlashBag()->add('success', "L'utilisateur a bien été ajouté.");

            return true;
        }

        return false;
    }
}
