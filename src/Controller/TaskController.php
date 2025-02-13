<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Room;
use App\Entity\User;
use App\Form\CreateTaskType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\RoomRepository;
use App\Repository\UserRoomRepository;


// #[Route('/task')]
final class TaskController extends AbstractController
{
    private $entityManager;
    private $roomRepository;
    private $roomUserRepository;
    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository, UserRoomRepository $roomUserRepository)
    {
        $this->roomUserRepository = $roomUserRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }
    // #[Route(name: 'app_task_index', methods: ['GET'])]
    // public function index(TaskRepository $taskRepository): Response
    // {
    //     return $this->render('task/index.html.twig', [
    //         'tasks' => $taskRepository->findAll(),
    //     ]);
    // }

    #[Route('/room/{id}/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        // dump('', $room, $request);
        // die();

        $memberInRoom = $this->roomRepository->findUserByRoom($room,"member", "joined");

        $adminInRoom = $this->roomRepository->findUserByRoom($room,"admin","joined")[0]->getUser();

        // dump($adminInRoom);
        // die();

        $task = new Task();
        $form = $this->createForm(CreateTaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump("co the luu roi a",$form->getData());
            // die();

            //DUNG TRANSACTION DE LUU TASK
            try {
                $this->entityManager->beginTransaction();
                $task->setRoom($room);
                $task->setLeader($adminInRoom);
                
                //LUU MEMBER
                // $task->setMember($memberInRoom ?? null);
                $memberId = $request->get("member");
                // dump($memberId);
                // die();
                if($memberId !== "null"){
                    $member = $this->entityManager->getRepository(User::class)->find($memberId);
                    // dump($member);
                    // die();
                    if ($member) {
                        $task->setMember($member);
                    }
                    
                }

                //LUU FILE NEU CO
                $file = $form->get("pathAttachment")->getData();
                if(file_exists($file)) {
                        $newFilename = uniqid().'.'.$file->guessExtension();
                        $file->move(
                            $this->getParameter('uploads_directory'),
                            $newFilename
                    );
                    $task->setPathAttachment($newFilename);
                }


                $this->entityManager->persist($task);

                $this->entityManager->flush();
                $this->entityManager->commit();

                // dump($task, $request);
                // die("");

            } catch (\Exception $exception ) {
                dump("Error in app_task_new", $exception->getMessage());
                die();
            }
            return $this->redirectToRoute('app_room_task', [
                'id' => $room->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        // else {
        //     $errors = [];
        //     foreach ($form->getErrors(true) as $error) {
        //         $errors[] = $error->getMessage();
        //     }
        //     dump($errors);
        //     die();
        // }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
            'room' => $room,
            // 'admin' => $adminInRoom,
            'members' => $memberInRoom,

        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

}
