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

        if (empty($users) || empty($rooms)) {
            $output->writeln("Cần có dữ liệu User và Room trước khi tạo UserRoom!\n");
            return Command::FAILURE;
        }

                // Danh sách trạng thái với tỉ lệ "joined" cao hơn
            $statuses = array_merge(
                    array_fill(0, 80, 'joined'), 
                    array_fill(0, 10, 'pending'),
                    array_fill(0, 10, 'left'), 
                    array_fill(0, 00, 'removed')
                );

        $statuses = ['joined', 'pending', 'left', 'removed'];
        $roles = ['admin', 'member'];

        // Mảng để theo dõi các user đã tham gia room nào
        $existingUserRooms = [];
        foreach ($users as $user) {
            // Chọn số lượng phòng ngẫu nhiên mà user này sẽ tham gia
            $userRooms = $faker->randomElements($rooms, rand(1, min(5, count($rooms)))); 

            foreach ($userRooms as $room) {
                // Đảm bảo user chưa có trong room này
                $userRoomKey = $user->getId() . '_' . $room->getId();
                if (isset($existingUserRooms[$userRoomKey])) {
                    continue; // Nếu user đã có trong room này, bỏ qua
                }

                $userRoom = new UserRoom();
                $userRoom->setUser($user);
                $userRoom->setRoom($room);
                $userRoom->setStatus($faker->randomElement($statuses));
                $userRoom->setRole($faker->randomElement($roles));
                $userRoom->setJoinDate($faker->dateTimeBetween('-1 year', 'now'));

                $this->entityManager->persist($userRoom);

                // Đánh dấu user đã tham gia room này
                $existingUserRooms[$userRoomKey] = true;

                $output->writeln("Đã tạo UserRoom: {$user->getLastName()} - Room {$room->getId()} - {$userRoom->getStatus()}\n");
            }
        }

        // $this->entityManager->flush();
        $output->writeln("'Seeding complete!'");
        return Command::SUCCESS;
    }
}
