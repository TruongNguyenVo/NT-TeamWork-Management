<?php

namespace App\EventSubscriber;

use App\Repository\RoomRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;

class SideBarSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private RoomRepository $roomRepository;
    private RequestStack $requestStack;
    private Security $security;
    

    public function __construct(Environment $twig, RoomRepository $roomRepository, RequestStack $requestStack,
    Security $security)
    {
        $this->twig = $twig;
        $this->roomRepository = $roomRepository;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }
        public function onKernelController(ControllerEvent $event): void
        {
            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                return;
            }

            $user = $this->security->getUser();

            $adminRoom = $this->roomRepository->findAllByRole("admin", $user);
            $memberRoom = $this->roomRepository->findAllByRole("member", $user);

            // dump($adminRoom, $memberRoom, $user);
            // die();

            // 🟢 Thêm biến navbar vào mọi trang
            $this->twig->addGlobal('sidebar', [
                'room' => [
                    'admin' => $adminRoom,
                    'member' => $memberRoom
                ],

            ]);
        }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
