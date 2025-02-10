<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\UserRoom;
use App\Form\ChangeStatusUserRoomType;
use App\Form\User;
use App\Form\RoomType;
use App\Form\AttendType;
use App\Repository\RoomRepository;
use App\Repository\UserRoomRepository;
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
    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository, UserRoomRepository $roomUserRepository)
    {
        $this->roomUserRepository = $roomUserRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
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
        $form = $this->createForm(AttendType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump("toi day roi",$request->request->all());
            // die();
            

            // neu tao phong thanh cong thi chuyen ve trang danh sach phong
            if($this->roomRepository->createRoom($room, $this->getUser())){
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
            else{
                //neu tao phong that bai thi chuyen ve trang tao phong
                return $this->redirectToRoute('app_room_new',[],  Response::HTTP_SEE_OTHER);
            }
            
            
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
        $form = $this->createForm(AttendType::class, $room);
        if($request->getMethod() == 'POST'){
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // Lấy giá trị của trường password
                $password = $form->get('password')->getData();
                $id = $form->get('id')->getData();

                $findRoom =  $this->roomRepository->findOneBy(['id' => $id]);
                $existUserInRoom = $this->roomRepository->existsUserInRoom($this->getUser(), $findRoom);

                // dump($existUserInRoom);
                // die();

                if($password == $findRoom->getPassword() && $existUserInRoom == false){
                    $userGroup = new UserRoom();
                    $userGroup->setUser($this->getUser());
                    $userGroup->setRoom($findRoom);
                    $userGroup->setRole("member");
                    $userGroup->setStatus("pending");
                    $entityManager->persist($userGroup);
                    $entityManager->flush();

                    // dump("toi post attend roi", $request, $password, $findRoom);
                    // die();
                    return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                }
                else{
                    return $this->redirectToRoute('app_room_attend', [], Response::HTTP_SEE_OTHER);
                }
                
            }
        }


        return $this->render('room/attend.html.twig', [
            'room' => $room,
            'form' => $form,
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

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
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
    #[Route(path:'/{id}/overview', name:'app_room_overview', methods: ['GET'])]
    public function overviewRoom(Request $request): Response
    {
        $roomId = $request->attributes->get('id');
        if($roomId !== null){
            $room = $this->roomRepository->find($roomId);
            if($room !== null){
                // dump($roomId, $request,$room);
                // die();
                $isAdmin = $this->roomRepository->isRole($this->getUser(), $room->getId(), "admin");
                if($isAdmin === true){
                    return $this->render('room/overview.html.twig',
                ['room'=> $room]);
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
    #[Route(path:'/{id}/member', name:'app_room_member', methods: ['GET'])]
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
                    // dump($memberInRoom);
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
        if ($roomUser) {
            // Cập nhật trạng thái
            if( $status === 'deny')
            {
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

    //HAM XEM OVERVIEW CUA THANH VIEN
    #[Route(path:'/{id}/overview/member', name:'app_room_overview', methods: ['GET'])]
    public function overviewMember(Request $request): Response
    {
        $roomId = $request->attributes->get('id');
        if($roomId !== null){
            $room = $this->roomRepository->find($roomId);
            if($room !== null){
                    return $this->render('room/overviewMember.html.twig',
                ['room'=> $room]);
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
