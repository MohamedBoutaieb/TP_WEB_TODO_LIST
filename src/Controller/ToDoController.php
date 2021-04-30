<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     */
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('todos')) {
            $todos=  [
                'Monday' => '100 push ups , 50 pull ups ,50 chin ups',
                'Wednesday' => '10 mn rope skipping, 100 squats, 30 reps deadlift  ',
                'Friday' => '5km run ,50 russian sit ups , 2mn plank'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "Welcome to your platform ");
        }
        return $this->render('to_do/index.html.twig', [

        ]);
    }

    /**
     * @Route ("/todo/add/{name}/{content}",name="addTodo")
     */
    public function addTodo($name, $content, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "List is not initialized yet!");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {

                $this->addFlash('error', "To Do already exists!");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "To Do $name has been added successfully");

            }


        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route ("/todo/delete/{name}",name="DeleteToDo")
     */
    public function DeleteToDo($name, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "List is not initialized yet!");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {

                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "To Do $name has been deleted successfully");
            } else {


                $this->addFlash('error', "To Do $name doesn't exist !");

            }


        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route ("/todo/updateName/{name}/{newName}/{content}",name="UpdateToDoName",defaults={"content" : "null"})
     */
    public function UpdateToDoName($name, $newName, $content, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "List is not initialized yet!");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$name]) && !isset($todos[$newName])) {
                if ($newName!='null'){
                $keys = array_keys($todos);
                $keys[array_search($name, $keys)] = $newName;

                $todos= array_combine($keys, $todos); }
                else {$newName=$name;}
               if($content!='null') $todos[$newName] = $content;

                $session->set('todos', $todos);
                $this->addFlash('success', "To Do $name has been updated successfully");
            } else if (isset($todos[$newName])){

                $this->addFlash('error', "To Do $newName already exists !");
            }
            else if (isset($todos[$newName])){


                $this->addFlash('error', "To Do $name doesn't exist !");

            }



        }
        return $this->redirectToRoute('todo');
    }
    /**
     * @Route ("/todo/update/{name}/{content}",name="UpdateToDoContent")
     */
    public function UpdateToDoContent($name, $content, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "List is not initialized yet!");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $todos[$name]= $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "To Do $name has been updated successfully");
            } else {


                $this->addFlash('error', "To Do $name doesn't exist !");

            }



        }
        return $this->redirectToRoute('todo');
    }


    /**
     * @Route ("/todo/reset",name="ResetToDo")
     */
    public function ResetToDo( SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "List is already reinitialized !");
        } else {
                session_unset();

                $todos=  [
                    'Monday' => '100 push ups , 50 pull ups ,50 chin ups',
                    'Wednesday' => '10 mn rope skipping, 100 squats, 30 reps deadlift  ',
                    'Friday' => '5km run ,50 russian sit ups , 2mn plank'
                ];
                $session->set('todos', $todos);
                $this->addFlash('success', "To Do List has been reset successfully");


        }
        return $this->redirectToRoute('todo');
    }

}