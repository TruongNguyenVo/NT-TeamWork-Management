<?php

namespace App\Command;

use App\Entity\Task;
use App\Entity\UserRoom;
use App\Entity\Room;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;



#[AsCommand(
    name: 'app:seed-tasks',
    description: 'Add a short description for your command',
)]
class SeedTasksCommand extends Command
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
        // task ve quan ly du an
        $tasks_manage_project = [
            'room_id' => 1,
            'data' => [
            [
                'name' => 'Phân công công việc',
                'content' => 'Phân công công việc cho từng thành viên trong nhóm',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-01',
                'end_date' => '2023-10-10',
                'review_date' => '2023-10-15',
                'finish_date' => '2023-10-20',
                'status' => 'done'
            ],
            [
                'name' => 'Lên kế hoạch dự án',
                'content' => 'Lên kế hoạch chi tiết cho dự án',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-05',
                'end_date' => '2023-10-12',
                'review_date' => '2023-10-17',
                'finish_date' => '2023-10-22',
                'status' => 'pending'
            ],
            [
                'name' => 'Thiết kế giao diện',
                'content' => 'Thiết kế giao diện người dùng cho dự án',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-08',
                'end_date' => '2023-10-18',
                'review_date' => '2023-10-23',
                'finish_date' => '2023-10-28',
                'status' => 'in_progress'
            ],
            [
                'name' => 'Phát triển backend',
                'content' => 'Phát triển các API và logic phía server',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-10',
                'end_date' => '2023-10-20',
                'review_date' => '2023-10-25',
                'finish_date' => '2023-10-30',
                'status' => 'review'
            ],
            [
                'name' => 'Kiểm thử chức năng',
                'content' => 'Kiểm thử các chức năng của dự án',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-12',
                'end_date' => '2023-10-22',
                'review_date' => '2023-10-27',
                'finish_date' => '2023-11-01',
                'status' => 'done'
            ],
            [
                'name' => 'Triển khai dự án',
                'content' => 'Triển khai dự án lên môi trường thực tế',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-15',
                'end_date' => '2023-10-25',
                'review_date' => '2023-10-30',
                'finish_date' => '2023-11-04',
                'status' => 'pending'
            ],
            [
                'name' => 'Đào tạo người dùng',
                'content' => 'Đào tạo người dùng sử dụng hệ thống',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-18',
                'end_date' => '2023-10-28',
                'review_date' => '2023-11-02',
                'finish_date' => '2023-11-07',
                'status' => 'pending'
            ],
            [
                'name' => 'Thu thập phản hồi',
                'content' => 'Thu thập phản hồi từ người dùng',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-20',
                'end_date' => '2023-10-30',
                'review_date' => '2023-11-04',
                'finish_date' => '2023-11-09',
                'status' => 'in_progress'
            ],
            [
                'name' => 'Cải tiến hệ thống',
                'content' => 'Cải tiến hệ thống dựa trên phản hồi',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-22',
                'end_date' => '2023-11-01',
                'review_date' => '2023-11-06',
                'finish_date' => '2023-11-11',
                'status' => 'review'
            ],
            [
                'name' => 'Bảo trì hệ thống',
                'content' => 'Bảo trì hệ thống định kỳ',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-25',
                'end_date' => '2023-11-05',
                'review_date' => '2023-11-10',
                'finish_date' => '2023-11-15',
                'status' => 'done'
            ],
            [
                'name' => 'Báo cáo tiến độ',
                'content' => 'Báo cáo tiến độ dự án cho quản lý',
                'path_attachment' => '',
                'result_content' => '',
                'result_attachment' => '',
                'start_date' => '2023-10-28',
                'end_date' => '2023-11-08',
                'review_date' => '2023-11-13',
                'finish_date' => '2023-11-18',
                'status' => 'cancelled'
            ],
            ],
        ];

        $room = $this->entityManager->getRepository(Room::class)->find($tasks_manage_project['room_id']);
        // lay danh sach tat ca thanh vien cua nhom room_1
        $members_data = $this->entityManager->getRepository(UserRoom::class)->findBy(['room' => $room,'status' =>'joined', 'role' => 'member']);
        $admin = $this->entityManager->getRepository(UserRoom::class)->findOneBy(['room'=> $room,'role'=> 'admin','status'=> 'joined']);
        // foreach ($members_data as $member) {
        //     $output->writeln($member->getUser()->getFullName());
        // }
        foreach($tasks_manage_project['data'] as $task){
            $new_task = new Task();
            $new_task->setName($task['name']);
            $new_task->setContent($task['content']);
            $new_task->setStatus($task['status']);
            $new_task->setPathAttachment($task['path_attachment']);
            $new_task->setResultContent($task['result_content']);
            $new_task->setResultAttachment($task['result_attachment']);
            $new_task->setStartDate(new \DateTime($task['start_date']));
            $new_task->setEndDate(new \DateTime($task['end_date']));
            $new_task->setReviewDate(new \DateTime($task['review_date']));
            $new_task->setFinishDate(new \DateTime($task['finish_date']));
            $new_task->setStatus($task['status']);
            $new_task->setLeader($admin->getUser());
            $random_member = $members_data[array_rand($members_data)];
            $new_task->setMember($random_member->getUser());
            $new_task->setRoom($room);
            $this->entityManager->persist($new_task);

            $output->writeln('Da tao task voi:'. $new_task->getId() .'Ten cong viec:'.$new_task->getName());
        }
        $this->entityManager->flush();
        $output->writeln("'Seeding complete!'");
        return Command::SUCCESS;

    }
}
