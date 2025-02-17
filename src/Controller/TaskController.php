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
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RoomRepository;
use App\Repository\UserRoomRepository;


// #[Route('/task')]
final class TaskController extends AbstractController
{
    private $entityManager;
    private $roomRepository;
    private $roomUserRepository;
    private $taskRepository;
    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository, UserRoomRepository $roomUserRepository, TaskRepository $taskRepository)
    {
        $this->roomUserRepository = $roomUserRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
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
                        $task->setStatus("in_progress");
                        $task->setMember($member);
                    }
                    
                }

                //LUU FILE NEU CO
                $file = $form->get("pathAttachment")->getData();
                if(file_exists($file)) {
                        $newFilename = "/uploads/" . uniqid().'.'.$file->guessExtension();
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

    #[Route('/room/{roomId}/task/{id}/edit', name: 'app_task_edit', methods: ['POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // $taskId = $request->get('idTaskShow');

        // dump($request, $task);
        // die();
        

        $task->setName($request->request->get('name'));
        $task->setContent($request->get("content"));
        $task->setStartDate(new \DateTime($request->request->get("startDate")) ?? null);
        $task->setEndDate(new \DateTime($request->request->get("endDate")) ?? null);
        $task->setStatus($request->request->get("status"));
        if($request->get("member")){
            $task->setMember($entityManager->getRepository(User::class)->find($request->get("member")) ?? null);
        }
        if($request->files->get("newPathAttachment")){
            //LUU FILE NEU CO
            $file = $request->files->get("newPathAttachment");
            if(file_exists($file)) {
                    $newFilename = "/uploads/" . uniqid().'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                );
                
                $task->setPathAttachment($newFilename);
            }
        }

        $entityManager->persist($task);
        $entityManager->flush();


        $roomId = $request->attributes->get('roomId');
        return $this->redirectToRoute('app_room_task', [
            'id' => $roomId,
        ], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/room/{roomId}/task/{id}/edit', name: 'app_task_edit', methods: ['POST'])]
    // public function edit(int $roomId, int $id): Response
    // {
    //     // dump('', $roomId, $id);
    //     // die();


    //     $result = [
    //         "message" => "done",
    //         "roomId" => $roomId,
    //         "id" => $id,
    //     ];

    //     return new JsonResponse($result);
    // }

    #[Route('/room/{roomId}/task/{id}/delete', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
        //     $entityManager->remove($task);
        //     $entityManager->flush();
        // }
        // else{
        //     dump('loi delete roi');
        //     die();
        // }

        if($task){
            $entityManager->remove($task);
            $entityManager->flush();
        }

        $roomId = $request->attributes->get('roomId');
        return $this->redirectToRoute('app_room_task', [
            'id' => $roomId,
            
        ], Response::HTTP_SEE_OTHER);
    }

    //HAM API DE TRA VE THONG TIN CUA TASK DUA VAO ID
    #[Route('/api/room/{roomId}/task/{id}', name:'api_app_task_show', methods: ['GET'])]
    public function apiShow(int $id, int $roomId): Response
    {
        $room = $this->roomRepository->find($roomId);
        $task = $this->taskRepository->find($id);
        if(!$task){
            dump("Loi o apiShow");
            die();
        }
        $memberInRoom = $this->roomRepository->findUserByRoom($room, "member", "joined");

        $arrMember = [];
        foreach($memberInRoom as $member){
            $arrMember[] = [
                "id" => $member->getUser()->getId(),
                "fullName" => $member->getUser()->getFullName(),
            ];
        }
        // dump($arrMember);
        // die();



        $taskData = [
        "id"=> $task->getId(),
        "name" => $task->getName(),
        'content' => $task->getContent(),
        'startDate' => $task->getStartDate() ? $task->getStartDate()->format('Y-m-d H:i:s') : null,
        'endDate' => $task->getEndDate() ? $task->getEndDate()->format('Y-m-d H:i:s') : null,
        'reviewDate' => $task->getReviewDate() ? $task->getReviewDate()->format('Y-m-d H:i:s') : null,
        'finishDate' => $task->getFinishDate() ? $task->getFinishDate()->format('Y-m-d H:i:s') : null,
        'status' => $task->getStatus(),
        // 'leader' => $task->getLeader() ? $task->getLeader()->getFullName() : null,
        'member' => $task->getMember() ? $task->getMember()->getFullName() : null,
        'memberId' => $task->getMember() ? $task->getMember()->getId() : null,
        'room' => $task->getRoom() ? $task->getRoom()->getName() : null,
        'pathAttachment' => $task->getPathAttachment(),
        'resultContent' => $task->getResultContent(),
        'resultAttachment' => $task->getResultAttachment(),
        'arrMember' => $arrMember,
    ];
    
        // dump($taskData);
        // die();

    // Trả về JSON response
    return new JsonResponse($taskData);
    }
}
