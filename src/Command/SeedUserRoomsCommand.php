<?php

namespace App\Command;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\UserRoom;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker\Factory;
use Doctrine\ORM\EntityManagerInterface;
#[AsCommand(
    name: 'app:seed-userRooms',
    description: 'Add a short description for your command',
)]
class SeedUserRoomsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('vi_VN');

        // Lấy danh sách user và room đã có từ database
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $rooms = $this->entityManager->getRepository(Room::class)->findAll();

        // foreach ($users as $user) {
        //     $output->writeln("User: {$user->getId()}\n");
        // }
        // die();

        if (empty($users) || empty($rooms)) {
            $output->writeln("Cần có dữ liệu User và Room trước khi tạo UserRoom!\n");
            return Command::FAILURE;
        }

        // Mảng để theo dõi các user đã tham gia room nào
        $existingUserRooms = [];
        foreach ($rooms as $room) {
            $roomAdmins = 0;
            $roomMembers = 0;

            foreach ($users as $user) {
            // Đảm bảo user chưa có trong room này
            $userRoomKey = $user->getId() . '_' . $room->getId();
            if (isset($existingUserRooms[$userRoomKey])) {
                continue; // Nếu user đã có trong room này, bỏ qua
            }

            $userRoom = new UserRoom();
            $userRoom->setUser($user);
            $userRoom->setRoom($room);

            // Nếu chưa có admin trong room này và user có id là 1, 2 hoặc 3, đặt user này làm admin
            if ($roomAdmins < 1 && in_array($user->getId(), [1, 2, 3])) {
                $userRoom->setRole('admin');
                $userRoom->setStatus('joined'); // Admin mặc định là joined
                $roomAdmins++;
            } else {
                $userRoom->setRole('member');
                $userRoom->setStatus('joined'); // Thành viên mặc định là joined
                $roomMembers++;
            }

            $userRoom->setJoinDate($faker->dateTimeBetween('-1 year', 'now'));

            $this->entityManager->persist($userRoom);

            // Đánh dấu user đã tham gia room này
            $existingUserRooms[$userRoomKey] = $userRoom->getRole();

            $output->writeln("Đã tạo UserRoom: {$user->getLastName()} - Room {$room->getId()} - {$userRoom->getStatus()} - {$userRoom->getRole()}\n");

            // Nếu room đã có ít nhất 1 admin và hơn 5 members, dừng thêm user vào room này
            if ($roomAdmins >= 1 && $roomMembers >= 5) {
                break;
            }
            }

            // Nếu room chưa có admin hoặc chưa có hơn 5 members, thêm user ngẫu nhiên vào room
            while ($roomAdmins < 1 || $roomMembers < 5) {
            $user = $faker->randomElement($users);
            $userRoomKey = $user->getId() . '_' . $room->getId();
            if (isset($existingUserRooms[$userRoomKey])) {
                continue; // Nếu user đã có trong room này, bỏ qua
            }

            $userRoom = new UserRoom();
            $userRoom->setUser($user);
            $userRoom->setRoom($room);

            if ($roomAdmins < 1) {
                $userRoom->setRole('admin');
                $userRoom->setStatus('joined'); // Admin mặc định là joined
                $roomAdmins++;
            } else {
                $userRoom->setRole('member');
                $userRoom->setStatus('joined'); // Thành viên mặc định là joined
                $roomMembers++;
            }

            $userRoom->setJoinDate($faker->dateTimeBetween('-1 year', 'now'));

            $this->entityManager->persist($userRoom);

            // Đánh dấu user đã tham gia room này
            $existingUserRooms[$userRoomKey] = $userRoom->getRole();

            $output->writeln("Đã tạo UserRoom: {$user->getLastName()} - Room {$room->getId()} - {$userRoom->getStatus()} - {$userRoom->getRole()}\n");
            }
        }

        // Đảm bảo tất cả các user đều có nhóm
        foreach ($users as $user) {
            $userInRoom = false;
            foreach ($rooms as $room) {
            $userRoomKey = $user->getId() . '_' . $room->getId();
            if (isset($existingUserRooms[$userRoomKey])) {
                $userInRoom = true;
                break;
            }
            }

            if (!$userInRoom) {
            $room = $faker->randomElement($rooms);
            $userRoom = new UserRoom();
            $userRoom->setUser($user);
            $userRoom->setRoom($room);
            $userRoom->setRole('member');
            $userRoom->setStatus('joined');
            $userRoom->setJoinDate($faker->dateTimeBetween('-1 year', 'now'));

            $this->entityManager->persist($userRoom);

            $output->writeln("Đã thêm User: {$user->getLastName()} vào Room {$room->getId()} dưới vai trò member\n");
            }
        }

        $this->entityManager->flush();
        $output->writeln("'Seeding complete!'");
        return Command::SUCCESS;
    }
}
