<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\NullCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    private $clientRegistry;
    private $router;
    private $entityManager;

    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        try {
            $accessToken = $this->fetchAccessToken($client);
        } catch (\Exception $e) {
            // Lỗi khi không thể lấy access token từ Google
            $this->addFlash('error', 'Không thể xác thực với Google, vui lòng thử lại.');
            return new RedirectResponse($this->router->generate('aaa'));
        }

        // Lấy thông tin người dùng từ Google
        $googleUser = $client->fetchUserFromToken($accessToken);

        // dump($googleUser); // DONE
        // die();

        // Lấy email của người dùng từ Google
        $email = $googleUser->getEmail();

        // Tìm kiếm người dùng trong cơ sở dữ liệu của bạn bằng email
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

    // Nếu người dùng không tồn tại, tạo mới người dùng
    if (!$user) {
        $user = new User();
        $user->setEmail($email);
        $user->setFirstname($googleUser->getFirstName());
        $user->setLastname($googleUser->getLastName());
        // $user->setFullname($googleUser->getName());

        // Lưu người dùng mới vào cơ sở dữ liệu
        $this->entityManager->persist($user);
        $this->entityManager->flush();


    }
    // dump("Toi day roi"); // DONE
    // die();
    // Return a SelfValidatingPassport
    return new SelfValidatingPassport(
        userBadge: new UserBadge($user->getUserIdentifier(), fn () => $user),
        badges: [
            new RememberMeBadge(),
        ]
    );
    
        
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dump($exception);
        die();

        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        // Redirect to the Google login page
        return new RedirectResponse($this->router->generate('connect_google_start'));
    }
}