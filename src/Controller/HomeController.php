<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoomRepository;

class HomeController extends AbstractController
{
    private $entityManager;

    // Constructor Injection
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'rooms' => $roomRepository->findAllByRole("admin", $this->getUser()),
        ]);
    }
    
    //trang thay doi thong tin nguoi dung
    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function showProfile(Request $request): Response
    {
        // dump("toi day roi", $request);
        // // die();
        $form = $this->createForm(ProfileType::class);
        return $this->render('home/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]); 
    }
    #[Route('/profile', name: 'app_profile_post', methods:['POST'])]
    public function updateProfile(Request $request): Response
    {
        // dump("toi day roi", $request);
        // die();
        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);

        if(true
            // $form->isSubmitted() && $form->isValid()
            ){
            $request_user = $form->getData();

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request_user->getEmail()]);

            // $this->entityManager->persist($user);
            if($user){
                $user->setFirstname($request_user->getFirstname());
                $user->setLastname($request_user->getLastname());
                // $user->setEmail($request_user->getEmail());
                if($request_user->getPassword() != null || $request_user->getPassword() != ""){
                    $user->setPassword($request_user->getPassword());
                    
                }
                // $user->setPassword($request_user->getPassword());
                $this->entityManager->flush();
            }
            return $this->redirectToRoute('app_profile');
        }
        else{
            // Example for logging errors
            foreach ($form->getErrors(true) as $error) {
                dump($error->getMessage());
            }
            die();
        }
        return $this->render('home/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
