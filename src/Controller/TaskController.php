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
use Symfony\Component\HttpClient\HttpClient;
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

    

    //HAM API DE TRA VE THONG TIN CUA TAT CA CAC TASK CUA 1 USER O 1 PHONG DUA VAO ID
    #[Route(path:'/api/room/{roomId}/consult', name:'api_app_consult_with_chatbot', methods: ['GET','POST'])]
    public function apiConsultWithChatBot(Request $request, int $roomId)
    {   
        // Lấy dữ liệu JSON từ body request
        $data = json_decode($request->getContent(), true);
        if($_ENV['GEMINI_API_KEY']){
            $httpClient = HttpClient::create();
            $method = 'POST';
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' .$_ENV['GEMINI_API_KEY'];
            $header = [
                'Content-Type' => 'application/json',
            ];
            $body = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => "List 5 day holidays in VietNam"]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "response_mime_type" => "application/json",
                    "response_schema" => [
                        "type" => "ARRAY",
                        "items" => [
                            "type" => "OBJECT",
                            "properties" => [
                                "holiday_name" => ["type" => "STRING"],
                                "holiday_date" => ["type" => "STRING", "format" => "date-time"]
                            ]
                        ]
                    ]
                ]
            ];
            
            try {
                $response = $httpClient->request($method, $url, [
                    'headers' => $header,
                    'json' => $body
                ]);
                //request thanh cong
                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    $chatResponse = $response->toArray();
    
                $task = [
                    [
                        'name' => 'abc',
                        'content' => 'adasd',
                        'startDate' => '1/2/2000',
                        'endDate' => '21/3/2023',
                        'member' => 1,
                    ],
                    [
                        'name' => 'adadas',
                        'content' => 'ndakjdnmczxjhc',
                        'startDate' => '1/2/2000',
                        'endDate' => '21/3/2023',
                        'member' => 2,
                    ],
                ];
                $response = [
                    'status' => 'success',
                    'tasks' => $task
                ];
                } 
                else{
                    $response = [
                        'status'=> 'error',
                        'message'=> $response->getStatusCode(),
                    ];
                }
                
            } catch (\Exception $exception) {
                $response =[
                    'status'=> 'error',
                    'message'=> $exception->getMessage(),
                ];
            }
            
            
    
            
            
        }
        else{
            $response =[
                'status'=> 'error',
                'message' => 'Hệ thống hiện không hoạt động, vui lòng thử lại sau.',
            ];
        }
        
        
        return new JsonResponse($response);
    }
}
