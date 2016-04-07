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

        return $this->render('LearningSymfonyTodolistBundle::main.html.twig', array(
            'newTodoForm' => $newTodoForm->createView(),
            'todos' => $todos
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
