<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\UserRoom;
use App\Form\ChangeStatusUserRoomType;
use App\Form\User;
use App\Form\RoomType;
use App\Form\AttendType;
use App\Repository\UserRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRoomRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/room')]
final class RoomController extends AbstractController
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

    // #[Route(name: 'app_room_index', methods: ['GET'])]
    // public function index(RoomRepository $roomRepository): Response
    // {
    //     return $this->render('room/index.html.twig', [
    //         'rooms' => $roomRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $room = new Room();
        $user = $this->getUser();
        $form = $this->createForm(AttendType::class, $room);
        $form->handleRequest($request);
        // dump($form);
        // die();
        if ($form->isSubmitted() && $form->isValid()) {
            // dump("toi day roi",$request->request->all());
            // die();
            

            // neu tao phong thanh cong thi chuyen ve trang danh sach phong
            // if(
            $this->roomRepository->createRoom($room, $this->getUser());
                // )){
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            // }
            // else{
            //     //neu tao phong that bai thi chuyen ve trang tao phong
            //     return $this->redirectToRoute('app_room_new',[],  Response::HTTP_SEE_OTHER);
            // }
            
            
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
            
        ]);
    }
    #[Route('/attend', name: 'app_room_attend', methods: ['GET', 'POST'])]
    public function attend(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        // dump($request->request->all());
        // die();
        $room = new Room();

        $user = $this->getUser();
        $pendingRoom = $this->roomRepository->findAllByRole('member',$user,'pending');
        // dump($pendingRoom);
        // foreach($pendingRoom as $room){
        //     dump($room[0]);
        // }
        // die();


        $form = $this->createForm(AttendType::class, $room);
        if($request->getMethod() == 'POST'){
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // Lấy giá trị của trường password
                $password = $form->get('password')->getData();
                $id = $form->get('id')->getData();

                // dump($id, $password);
                // die();
                $findRoom =  $this->roomRepository->findOneBy(['id' => $id]);
                if(!$findRoom){
                    $this->addFlash('error','ID phòng không đúng. Vui lòng kiểm tra lại.');
                    return $this->redirectToRoute('app_room_attend');
                }
                $existUserInRoomWithJoined = $this->roomRepository->existsUserInRoom($this->getUser(), $findRoom, "joined");

                // dump($findRoom, $existUserInRoom);
                // die();
                // dump($existUserInRoom);
                // die();

                if($existUserInRoomWithJoined == true){
                    return $this->redirectToRoute('app_room_show', [
                        'id'=> $findRoom->getId(),
                    ], Response::HTTP_SEE_OTHER);
                }

                $existUserInRoomWithPending =$this->roomRepository->existsUserInRoom($this->getUser(), $findRoom, "pending");
                

                if($password == $findRoom->getPassword() && $existUserInRoomWithJoined == false){
                    $userGroup = new UserRoom();
                    $userGroup->setUser($this->getUser());
                    $userGroup->setRoom($findRoom);
                    $userGroup->setRole("member");
                    $userGroup->setStatus("pending");
                    $entityManager->persist($userGroup);
                    $entityManager->flush();

                    // dump("toi post attend roi", $request, $password, $findRoom);
                    // die();
                    $this->addFlash('success', 'Bạn đã yêu cầu tham gia nhóm, hãy đợi trưởng nhóm duyệt.');

                    return $this->redirectToRoute('app_room_attend');
                }
                if($existUserInRoomWithPending == true){
                    
                    $this->addFlash('error', 'Bạn đã yêu cầu tham gia nhóm rồi, hãy đợi trưởng nhóm duyệt.');

                    return $this->redirectToRoute('app_room_attend');
                }
                else{
                    $this->addFlash('error', 'Mật khẩu không chính xác. Vui lòng kiểm tra lại.');

                    return $this->redirectToRoute('app_room_attend', [], Response::HTTP_SEE_OTHER);
                }
                
            }
        }


        return $this->render('room/attend.html.twig', [
            'room' => $room,
            'form' => $form,
            'pendingRoom' => $pendingRoom,
        ]);
    }

    #[Route('/{id}', name: 'app_room_show', methods: ['GET'])]
    public function show(Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        // dump($room);
        // die();
        return $this->render('room/show.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_room_edit', methods: ['POST'])]
    public function edit(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        // dump($request, $room);
        // die();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_room_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        // dump("o xoa roi", $request, $room);
        // die();
        if($request->getMethod() == 'POST'){
            if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->getPayload()->getString('_token'))) {
                try{
                    $entityManager->beginTransaction();
                    // Tìm tất cả các record trong bảng userroom liên quan đến phòng
                    $userRooms = $entityManager->createQueryBuilder()
                        ->select('ur')
                        ->from(UserRoom::class, 'ur')
                        ->where('ur.room = :room')
                        ->setParameter('room', $room)
                        ->getQuery()
                        ->getResult();


                    // Xóa các record đó
                    foreach ($userRooms as $userRoom) {
                        $entityManager->remove($userRoom);
                    }

                    // Xóa phòng
                    $entityManager->remove($room);
                    // dump("xoa thanh cong");
                    // die();
                    $entityManager->flush();

                    $entityManager->commit();
                }
                catch(\Exception $e){
                    $entityManager->rollback();
                    dump($e->getMessage());
                    die();
                }

                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
            else{
                dump("bug roi");
                die();
            }
            
        }
        return $this->render('room/delete.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    //hien thi trang XEM TONG QUAN
    #[Route(path:'/{id}/overview', name:'app_room_overview', methods: ['GET', 'POST'])]
    public function overviewRoom(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        // dump($room);
        // die('');
        if($room == null){
            $room = $this->roomRepository->find($request->attributes->get('id'));
            // dump($room);
            // die();
        }
        $membersInRoom = $this->roomRepository->findUserByRoom($room,"member", "joined");
        $temp = $this->roomRepository->findUserByRoom($room, "admin","joined");
        
        // dump($temp, );
        // die();

        $adminInRoom = $temp[0]->getUser();
        

        $tasks = $this->taskRepository->findBy(["room" => $room]);

        $memberWithTasks =[];
        //gan tat ca cac thanh vien voi nhiem vu = 0
        // Khởi tạo tất cả member với số task = 0
        foreach ($membersInRoom as $member) {
            $memberWithTasks[$member->getUser()->getId()] = [
                'member' => $member, // Lưu luôn đối tượng Member
                'taskCount' => 0
            ];
        }
        // dump($memberWithTasks);
        // die();
        
        // Đếm số lượng task theo member
        foreach ($tasks as $task) {
            $member = $task->getMember();
            if ($member) {
                $memberWithTasks[$member->getId()]['taskCount']++;
                // dump($member);

            }
        }

        // dump($tasks, $memberWithTasks, $membersInRoom);
        // die();

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            try{
                $entityManager->beginTransaction();
                    // dump($request->get('groupLeader'), $adminInRoom->getId());
                    // die();

                if((int)$request->get('groupLeader') !== $adminInRoom->getId()){
                    // dump("vao if roi");
                    // die();

                    // gan role member cho adminInRoom->getId -> gan role admin cho id request->get(groupLeader)
                    $adminToMember = $this->roomUserRepository->findOneBy(['room' => $room, 'user' =>$adminInRoom]);

                    $user = $this->userRepository->find($request->get('groupLeader'));
                    $memberToAdmin = $this->roomUserRepository->findOneBy(['room'=> $room,'user' =>$user]);

                    // dump($adminToMember, $memberToAdmin, $user, $request->get('groupLeader'));
                    // die();

                    if($memberToAdmin !== null && $adminToMember !==null){
                        // dump("vao if roi");
                        // die();
                        $memberToAdmin->setRole('admin');
                        $adminToMember->setRole("member");
                        $entityManager->persist($memberToAdmin);
                        $entityManager->persist($adminToMember);
                    }
                }
                else{
                    return $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));
                }
                $entityManager->persist($room);
                $entityManager->flush();

                $entityManager->commit();
            }
            catch(\Exception $e){
                $entityManager->rollback();
                dump($e->getMessage());
                die();
            }
            // dump("toi commit roi");
            // die();
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

            
        }
        $roomId = $request->attributes->get('id');
        if($roomId !== null){
            $room = $this->roomRepository->find($roomId);
            if($room !== null){
                // dump($roomId, $request,$room);
                // die();
                $isAdmin = $this->roomRepository->isRole($this->getUser(), $room->getId(), "admin");
                if($isAdmin === true){

                    // dump($membersInRoom, $adminInRoom);
                    // die();
                    return $this->render('room/overview.html.twig',
                [
                    'room'=> $room,
                    'members' => $membersInRoom,
                    'admin' => $adminInRoom,
                    'form' => $form,
                    'tasks' => $tasks,
                    'memberWithTasks' =>$memberWithTasks, // nay se tra ve ten cua thanh vien va so luong nhiem vu
                ]);
                    // dump($isAdmin, $request);
                    // die();
                }
                else{
                    return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                }
            }
            else{
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }

        // dump($roomId, $request);
        // die();
        // return $this->render('room/overview.html.twig');
        }
        else{
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    //thi thi trang THONG TIN THANH VIEN
    #[Route(path:'/{id}/member', name:'app_room_member', methods: ['GET', 'POST'])]
    public function viewMember(Request $request): Response
    {
        // dump($request);
        // die();

        $roomId = $request->attributes->get('id');
        if($roomId !== null){
            $room = $this->roomRepository->find($roomId);
            if($room !== null){
                // dump($roomId, $request,$room);
                // die();
                $isAdmin = $this->roomRepository->isRole($this->getUser(), $room->getId(), "admin");
                if($isAdmin === true){
                    $memberInRoom = $this->roomRepository->findUserByRoom($room->getId(),);
                    // dump($memberInRoom[2]->getUser()->getMember());
                    // die();

                    return $this->render('room/member.html.twig', [
                        'room' => $room,
                        'members' => $memberInRoom,
                        // 'form' => $form->createView(),
                    ]);
                    // dump($isAdmin, $request);
                    // die();
                }
                else{
                    return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                }
            }
            else{
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    //THAY DOI TRANG THAI QUAN LY THANH VIEN
    #[Route(path:'/{id}/member/status', name:'app_room_member_status', methods: ['POST'])]
    public function changeStatus(Request $request): Response
    {
        $userId = $request->request->get('userId');
        $status = $request->request->get('status');
        $roomId = $request->attributes->get('id');

        $roomUser = $this->roomUserRepository->findOneBy([
            'user' => $userId,
            'room' => $roomId,
        ]);
        // dump($roomUser);
        // die();

        if ($roomUser) {
            // Cập nhật trạng thái
            if( $status === 'deny')
            {
                // dump();
                // die();
                $this->entityManager->remove($roomUser);
            }
            else{
                $roomUser->setStatus($status);
                
            }
                $this->entityManager->flush();
                // dump($request->request->all(), $userId, $status, $roomId);
                // die();
            // Chuyển hướng về trang thông tin thành viên
            return $this->redirectToRoute('app_room_member', ['id' => $roomId]);

        }

        // dump($request->request->all(), $userId, $status, $roomId);
        // die();
            // Nếu không tìm thấy UserRoom, chuyển hướng về trang chủ
        return $this->redirectToRoute('app_home');
    }

    //HAM HIEN THI TRANG THONG TIN TASK
    #[Route(path:'/{id}/task', name:'app_room_task', methods:['GET'])]
    public function viewTask(Request $request, Room $room):Response{

        $roomId = $request->attributes->get('id');

        $tasks = $this->taskRepository->findBy(["room" => $room]);

        // dump($tasks);
        // die();

        // dump('', $room);
        // die();
        return $this->render('task/index.html.twig',
        [
            'room' => $room,
            'tasks' => $tasks,
        ]);
    }

    //HAM XEM OVERVIEW CUA THANH VIEN
    #[Route(path:'/{id}/overview/member', name:'app_room_overview_member', methods: ['GET'])]
    public function overviewMember(Request $request): Response
    {
        
        $roomId = $request->attributes->get('id');


        if($roomId !== null){
            $room = $this->roomRepository->find($roomId);
            if($room !== null){
                $user = $this->getUser();
                $tasksOfUser = $this->taskRepository->findBy(['room'=> $room, 'member'=>$user]);
                $tasksDone =$this->taskRepository->findBy(['room'=>$room,'status'=>'done']);
                // dump($tasksOfUser, $user, $tasksDone);
                // die();
                    return $this->render('room/overviewMember.html.twig',
                [
                    'room'=> $room,
                    'tasksOfUser' => $tasksOfUser,
                    'tasksDone' =>$tasksDone,
                ]);
            }
            else{
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }
    
}
