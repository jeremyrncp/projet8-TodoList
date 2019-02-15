<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\Handler\Task\CreateTaskHandler;
use AppBundle\Form\Handler\Task\EditTaskHandler;
use AppBundle\Form\TaskType;
use AppBundle\OptionsResolver\FormHandlerOptionsResolver;
use AppBundle\Security\TaskVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        if ($this->getUser()->hasRole('ROLE_ADMIN')) {
            $tasksList = $this->getDoctrine()->getRepository('AppBundle:Task')->findUserAndAnonymousTask(
                $this->getUser()
            );
        } else {
            $tasksList = $this->getDoctrine()->getRepository('AppBundle:Task')->findBy(
                ['user' => $this->getUser()]
            );
        }
      
        return $this->render('task/list.html.twig', ['tasks' => $tasksList]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     *
     * @param Request $request
     * @param CreateTaskHandler $createTaskHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InvalidTypeException
     * @throws \AppBundle\Exception\MissingNecessaryOperation
     */
    public function createAction(Request $request, CreateTaskHandler $createTaskHandler)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        $createTaskHandler->setOptionsResolver(new FormHandlerOptionsResolver(['entity' => $task]));

        if ($createTaskHandler->handle($form)) {
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task $task
     * @param Request $request
     * @param EditTaskHandler $editTaskHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InvalidTypeException
     */
    public function editAction(Task $task, Request $request, EditTaskHandler $editTaskHandler)
    {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($editTaskHandler->handle($form)) {
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task);

        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $this->denyAccessUnlessGranted(TaskVoter::DELETE, $task);

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
