<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class GoogleController extends AbstractController
{
    // #[Route('/google', name: 'app_google')]
    // public function index(): Response
    // {
    //     return $this->render('google/index.html.twig', [
    //         'controller_name' => 'GoogleController',
    //     ]);
    // }
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        // Chuyển hướng đến Google
        return $clientRegistry->getClient('google')->redirect([
            'email', 'profile',
        ]);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheck(ClientRegistry $clientRegistry): RedirectResponse
    {
        try {
            $client = $clientRegistry->getClient('google');
            $googleUser = $client->fetchUser();

            // Lấy thông tin người dùng từ Google
            $email = $googleUser->getEmail();
            $name = $googleUser->getName();

            // // Kiểm tra xem người dùng đã tồn tại trong cơ sở dữ liệu chưa
            // $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                return $this->redirectToRoute('/');
            }

            // Đăng nhập người dùng
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('/'); // Redirect sau khi đăng nhập thành công
        } catch (AuthenticationException $e) {
            return $this->redirectToRoute('aaaaa'); // Redirect nếu có lỗi xảy ra
        }
    }
}
