<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[Route('/task', name: 'task_')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('task_index');
        }

        // Используйте $form->createView()
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('task_index');
        }

        // Используйте $form->createView()
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }

    #[Route('/toggle/{id}', name: 'toggle_status')]
    public function toggleStatus(Task $task, EntityManagerInterface $entityManager): Response
    {
        // Инвертируем статус задачи
        $task->setCompleted(!$task->isCompleted());
        $entityManager->flush();

        return $this->json(['success' => true]); // Возвращаем JSON-ответ
    }
//    #[Route('/toggle/{id}', name: 'toggle_status')]
//    public function toggleStatus(
//        Task $task,
//        EntityManagerInterface $entityManager,
//        Request $request,
//        TokenGeneratorInterface $tokenGenerator,
//        TokenStorageInterface $tokenStorage
//    ): Response {
//        // Проверка CSRF-токена
//        $submittedToken = $request->headers->get('X-CSRF-Token');
//        $expectedToken = $tokenGenerator->generateCsrfToken('task_toggle');
//
//        if (!$tokenStorage->getToken('task_toggle') || !hash_equals($expectedToken, $submittedToken)) {
//            return $this->json(['error' => 'Invalid CSRF token'], 400);
//        }
//
//        // Инвертируем статус задачи
//        $task->setCompleted(!$task->isCompleted());
//        $entityManager->flush();
//
//        return $this->json(['success' => true]);
//    }
}