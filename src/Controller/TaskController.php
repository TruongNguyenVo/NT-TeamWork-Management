<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Room;
use App\Entity\User;
use App\Form\CreateTaskType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTime;
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
use function Symfony\Component\Clock\now;


// #[Route('/task')]
final class TaskController extends AbstractController
{
    private $entityManager;
    private $roomRepository;
    private $roomUserRepository;
    private $taskRepository;
    private $userRepository;
    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository, UserRoomRepository $roomUserRepository, TaskRepository $taskRepository, UserRepository $userRepository)
    {
        $this->roomUserRepository = $roomUserRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
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

    #[Route('/room/{roomId}/overview/member/task/{id}', name: 'app_task_show_member', methods: ['GET','POST'])]
    public function show(Request $request, $roomId, $id): Response
    {   
        $room = $this->roomRepository->find($roomId);
        $task = $this->taskRepository->find($id);
        // dump($task);
        // die();
        

        if ($request->isMethod('POST')) {
            $action = $request->get('action');
            // dump($action);
            // die();

            $resultContent = $request->get('resultContent');
            $resultFile = $request->files->get('newResultAttachment');
            
            if($resultFile){
                $newFilename = "/uploads/" . uniqid().'.'.$resultFile->guessExtension();
                $resultFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $task->setResultAttachment($newFilename);
                // dump($newFilename);
            }
            if($resultContent){
                $task->setResultContent($resultContent);
            }
            if($action == 'submit'){
                $task->setStatus('review');
                $task->setFinishDate = new \DateTime();
            }
            if($action == 'unreview'){
                $task->setStatus('in_progress');
            }
            // dump($resultContent);
            // die();

            
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            

            // dump($resultContent);
            // die();
        }
        return $this->render('task/showMember.html.twig', [
            'room' => $room,
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
        $memberInRoom = $this->roomRepository->findUserByRoom($room, null, "joined");

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
    

    //HAM API DE TRA VE THONG TIN CUA TAT CA CAC TASK CUA 1 USER O 1 PHONG DUA VAO ID
    #[Route(path:'/api/room/{roomId}/member/{id}/tasks', name:'api_app_task_by_member', methods: ['POST'])]
    public function apiShowAllTaskByUserIdInRoom(int $roomId, int $id)
    {
        $room = $this->roomRepository->find($roomId);
        $user = $this->userRepository->find($id);
        $tasks = $this->taskRepository->findBy(['room' => $room, 'member' => $user]);

        $dataRespond =[];

        foreach ($tasks as $task) {
            $dataRespond[] = [
                'name' => $task->getName(),
                'startDate' => $task->getStartDate()->format('Y-m-d H:i:s'),
                'endDate' => $task->getEndDate()? $task->getEndDate()->format('Y-m-d H:i:s') : "",
                'finishDate' => $task->getFinishDate() ? $task->getFinishDate()->format('Y-m-d H:i:s') : "",
                'status' => $task->getStatusLabel(),
            ];
        }
        $userData = [
            'fullName' => $user->getFullName(),
        ];

        $response = [
            'user' => $userData,
            'tasks' => $dataRespond
        ];
        return new JsonResponse($response);
    }
    
    #[Route(path:'/api/room/{roomId}/consult', name:'api_app_consult_with_chatbot', methods: ['POST'])]
    public function apiConsultWithChatBot(Request $request, int $roomId)
    {   
        // Lấy dữ liệu JSON từ body request
        $data = json_decode($request->getContent(), true);
        // $temp = [
        //     'taskDescription' => $data['taskDescription'] ?? null, 
        // ];

        
            $roomId = $data['roomId'];
            $quantityMember = $data['quantityMember'] ?? 1;
            $roomName= $data['roomName'] ??'';
            $roomDescription = $data['roomDescription'] ?? null;
            $roomRequire=$data['roomRequire'] ?? '';
            $roomStart=$data['roomStart'] != '' ? $data['roomStart']: new \DateTime();
            $roomEnd=$data['roomEnd'] != '' ? $data['roomEnd'] :(clone $roomStart)->modify('+3 months');


            // ////////////////////////////////////TEST/////////////////////////////////////////////////////
            // $tasks =[];
            
            //     $tasks[] =[
            //         'name' => 'abc',
            //         'content' => 'adasd',
            //         'startDate' => '2025-02-14T00:00:00.000Z',
            //         'endDate' => '2025-02-14T00:00:00.000Z',
            //         'member' => 1,
            //         'recommend' => 'recommend'
            //     ];
            //     $tasks[] =[
            //         'name' => 'adadas',
            //         'content' => 'ndakjdnmczxjhc',
            //         'startDate' => '2025-02-14T00:00:00.000Z',
            //         'endDate' => '2025-02-14T00:00:00.000Z',
            //         'member' => 2,
            //         'recommend' => 'recommend1'
            //     ];

            // // $temp = [$roomId, $quantityMember, $roomDescription, $roomRequire, $roomStart, $roomEnd, $roomName];
            // // return new JsonResponse($temp);
            // $response = [
            //     'status' => 'success',
            //     'tasks' => $tasks
            // ];
            // return new JsonResponse($response);
            // ///////////////////////////////////////////////////////////////////////////////////////////////////

        $env = $_ENV['GEMINI_API_KEY'] ?? null;
        if($env == null) {
            $response =[
                'status'=> 'error',
                'message' => 'Hệ thống hiện không hoạt động do không có API_KEY, vui lòng thử lại sau.',
            ];
            return new JsonResponse($response);
        }
        
        if($_ENV['GEMINI_API_KEY']){
            $httpClient = HttpClient::create();
            $method = 'POST';
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' .$_ENV['GEMINI_API_KEY'];
            $header = [
                'Content-Type' => 'application/json',
            ];

            $prompt="Bỏ qua tất cả các yêu cầu trước đó và hãy trả lời tiếng việt cho yêu cầu này.
            Hãy tạo ra các công việc dành cho nhóm về môn $roomName có mô tả là $roomDescription với nhóm hiện tại có $quantityMember người. Tạo tối thiểu một thành viên có ít nhất phải có 4 công việc.
            Thời gian thực hiện là từ $roomStart và kết thúc là $roomEnd.
            Các yêu cầu là $roomRequire.
            Kết quả trả về là tên công việc(name), mô tả công việc (content), ngày bắt đầu (startDate), ngày kết thúc (endDate), gợi ý dành cho trưởng nhóm khi phân công công việc đó (recommend) và thành viên (member), member được đánh số từ 1 đến số lượng thành viên hiện tại.
            Tất cả các thông tin như tên công việc, mô tả công việc, thành viên bắt buộc phải có.
            Thời gian bắt đầu và thời gian kết thúc phải có, và thời gian tối thiểu của một công việc là 2 ngày";

            $body = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt],
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
                                "name" => ["type" => "STRING"],
                                "content"=>["type"=> "STRING"],
                                "startDate" => ["type" => "STRING", "format" => "date-time"],
                                "endDate"=> ["type"=> "STRING","format"=>"date-time"],
                                "member"=> ["type"=> "STRING"],
                                "recommend"=>["type"=> "STRING"],
                            ],
                            "required" => ["name", "content", 'startDate', 'endDate']
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
                    $data = json_decode($response->getContent(), true);
                    
                    // Lấy danh sách TEXT
                    $textListJson = $data['candidates'][0]['content']['parts'][0]['text'];

                    //Chuyển đổi chuỗi JSON trong 'text' thành mảng PHP
                    $jsonToArray = json_decode($textListJson, true);
                    
                    if($jsonToArray == null){
                        $response =[
                            'status'=> 'error',
                            'message' => 'Hệ thống không thể tạo công việc, vui lòng thử lại sau.',
                            
                        ];
                        return new JsonResponse($response);
                    }

                    $tasks =[];

                    foreach($jsonToArray as $task){
                        $tasks[] = [
                            'name' => $task['name'] ? $task['name'] : 'N/A',
                            'content' => $task['content'] ? $task['content']: 'N/A',
                            'startDate' => $task['startDate'] ? $task['startDate']: 'N/A',
                            'endDate' => $task['endDate'] ? $task['endDate']: 'N/A',
                            'member' => $task['member'] ? $task['member'] : 'N/A',
                            'recommend'=>$task['recommend'] ? $task['recommend'] : 'N/A',
                        ];
                    }

                    
                // $task = [
                //     [
                //         'name' => 'abc',
                //         'content' => 'adasd',
                //         'startDate' => '1/2/2000',
                //         'endDate' => '21/3/2023',
                //         'member' => 1,
                //     ],
                //     [
                //         'name' => 'adadas',
                //         'content' => 'ndakjdnmczxjhc',
                //         'startDate' => '1/2/2000',
                //         'endDate' => '21/3/2023',
                //         'member' => 2,
                //     ],
                // ];
                $response = [
                    'status' => 'success',
                    'tasks' => $tasks
                ];
                }
                else{
                    $response = [
                        'status'=> 'error',
                        'message'=> $response->getStatusCode(),
                    ];
                    return new JsonResponse($response);
                }
                
            } catch (\Exception $exception) {
                $response =[
                    'status'=> 'error',
                    'message'=> $exception->getMessage(),
                ];
                return new JsonResponse($response);
            }
        }
        
        return new JsonResponse($response);
    }

    // API DUNG DE PREDICT PERCENTAGE CUA TASK
    #[Route(path:'/api/room/{roomId}/task/{id}/predict', name:'api_app_task_predict', methods: ['POST'])]
    public function apiPredictPercent(Request $request, string $id, string $roomId): JsonResponse
    {
        // lay danh sach cac id cua thanh vien trong room
        //gọi api của flask (truyen id cua cac thanh vien trong room)
        $httpCLient_predict = HttpClient::create();
        $method = 'POST';
        $url = 'http://127.0.0.1:5000/predict';
        $header = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            $input = []
        ];

        try {
            $response = $httpCLient_predict->request($method, $url, [
                'headers' => $header,
                'json' => $body
            ]);
             //request thanh cong
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data =[
                    "vo nguyen" => "90%",
                    "nguyen van a" => "80%",
                    "nguyen van b" => "70%",
                    "nguyen van c" => "60%",
                ];
            }
            else{
                $response = [
                    'status'=> 'error',
                    'message'=> $response->getStatusCode(),
                ];
                return new JsonResponse($response);
            }
        } catch (\Exception $exception) {
            $response = [
                'status'=> 'error',
                'message'=> $exception->getMessage(),
            ];

            return new JsonResponse($response);
        }

        
        $response = [
            "status" => "success",
            "data" => $data,
            // "test" => "test",
            
        ];

        return new JsonResponse($response);
    }
}
