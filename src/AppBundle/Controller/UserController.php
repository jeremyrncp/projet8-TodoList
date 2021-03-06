<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Handler\User\CreateUserHandler;
use AppBundle\Form\Handler\User\EditUserHandler;
use AppBundle\Form\UserType;
use AppBundle\OptionsResolver\FormHandlerOptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, CreateUserHandler $createUserHandler)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        $createUserHandler->setOptionsResolver(new FormHandlerOptionsResolver(['entity' => $user]));

        if ($createUserHandler->handle($form)) {
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, EditUserHandler $editUserHandler)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        $editUserHandler->setOptionsResolver(new FormHandlerOptionsResolver(['entity' => $user]));

        if ($editUserHandler->handle($form)) {
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
