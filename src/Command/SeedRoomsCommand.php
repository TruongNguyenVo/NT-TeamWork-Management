<?php

namespace App\Command;

use App\Entity\Room;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker\Factory as FakerFactory;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:seed-rooms',
    description: 'Add a short description for your command',
)]
class SeedRoomsCommand extends Command
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
        $rooms = [
            ['name' => 'Quản lý dự án', 'description' => 'Nhóm thảo luận về phương pháp quản lý dự án nhóm.'],
            ['name' => 'Lập trình phần mềm', 'description' => 'Nhóm sinh viên phát triển phần mềm thực tế.'],
            ['name' => 'Thiết kế cơ sở dữ liệu', 'description' => 'Thảo luận về thiết kế và tối ưu hóa cơ sở dữ liệu.'],
            ['name' => 'Nghiên cứu trí tuệ nhân tạo', 'description' => 'Nhóm nghiên cứu AI và học máy.'],
            ['name' => 'Thiết kế UX/UI', 'description' => 'Chia sẻ kinh nghiệm thiết kế giao diện người dùng.'],
            ['name' => 'An toàn thông tin', 'description' => 'Nhóm học về bảo mật mạng và an ninh mạng.'],
            ['name' => 'Phát triển web', 'description' => 'Thảo luận về phát triển website và công nghệ mới.'],
            ['name' => 'Lập trình ứng dụng di động', 'description' => 'Nhóm phát triển ứng dụng Android & iOS.'],
            ['name' => 'Đồ án tốt nghiệp', 'description' => 'Nhóm sinh viên thực hiện luận văn tốt nghiệp.'],
            ['name' => 'Khởi nghiệp công nghệ', 'description' => 'Nhóm thảo luận về ý tưởng khởi nghiệp và startup.'],
        ];
        
        foreach( $rooms as $data ){
            $room = new Room();
            $room->setName($data['name']);
            $room->setDescription($data['description']);
            $room->setPassword("123456");
            $room->setCreateDate(new \DateTime());

            $this->entityManager->persist($room);
            $output->writeln("Created user: {$room->getName()} {$room->getPassword()}");
        }

        $this->entityManager->flush();
        $output->writeln('Seeding complete!');
        return Command::SUCCESS;
    }
}
