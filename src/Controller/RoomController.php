<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\UserRoom;
use App\Form\User;
use App\Form\RoomType;
use App\Repository\RoomRepository;
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
    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository)
    {
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
        $form = $this->createForm(RoomType::class, $room);
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
}
