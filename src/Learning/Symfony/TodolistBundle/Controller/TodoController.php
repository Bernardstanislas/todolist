<?php

namespace Learning\Symfony\TodolistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Learning\Symfony\TodolistBundle\Entity\Todo;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    public function indexAction(Request $request)
    {
        $newTodoForm = $this->createNewTodoForm();

        $todos = $this->getTodos();

        $undoneTodoCount = $this->getUndoneTodos();

        return $this->render('LearningSymfonyTodolistBundle::main.html.twig', array(
            'newTodoForm' => $newTodoForm->createView(),
            'todos' => $todos,
            'undoneCount' => $undoneTodoCount
        ));
    }

    public function newAction(Request $request)
    {
        $postData = $request->request->get('form');
        $newTodo = new Todo();
        $newTodo->setDone(false);
        $newTodo->setContent($postData['content']);

        $this->saveTodo($newTodo);

        return $this->redirect($this->generateUrl('index'));
    }

    private function getTodos()
    {
        $repository = $this->getDoctrine()
        ->getRepository('LearningSymfonyTodolistBundle:Todo');
        return $repository->findAll();
    }

    private function getUndoneTodos()
    {
        $repository = $this->getDoctrine()
        ->getRepository('LearningSymfonyTodolistBundle:Todo');
        $query = $repository->createQueryBuilder('undone')
        ->select('COUNT(undone)')
        ->where('undone.done = :done')
        ->setParameter('done', '0')
        ->getQuery();
        return $query->getSingleScalarResult();
    }

    private function saveTodo($todo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();
    }

    private function createNewTodoForm()
    {
        $newTodoForm = $this->createFormBuilder()
        ->setAction($this->generateUrl('add_new_task'))
        ->add('content', 'text')
        ->getForm();
        return $newTodoForm;
    }
}
