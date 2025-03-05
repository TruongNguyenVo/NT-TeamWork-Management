<?php

namespace App\Controller;

use App\Entity\TaskDependency;
use App\Form\TaskDependencyType;
use App\Repository\TaskDependencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/dependency')]
final class TaskDependencyController extends AbstractController
{
    #[Route(name: 'app_task_dependency_index', methods: ['GET'])]
    public function index(TaskDependencyRepository $taskDependencyRepository): Response
    {
        return $this->render('task_dependency/index.html.twig', [
            'task_dependencies' => $taskDependencyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_dependency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskDependency = new TaskDependency();
        $form = $this->createForm(TaskDependencyType::class, $taskDependency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($taskDependency);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_dependency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_dependency/new.html.twig', [
            'task_dependency' => $taskDependency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_dependency_show', methods: ['GET'])]
    public function show(TaskDependency $taskDependency): Response
    {
        return $this->render('task_dependency/show.html.twig', [
            'task_dependency' => $taskDependency,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_dependency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskDependency $taskDependency, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskDependencyType::class, $taskDependency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_task_dependency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_dependency/edit.html.twig', [
            'task_dependency' => $taskDependency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_dependency_delete', methods: ['POST'])]
    public function delete(Request $request, TaskDependency $taskDependency, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taskDependency->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($taskDependency);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_dependency_index', [], Response::HTTP_SEE_OTHER);
    }
}
