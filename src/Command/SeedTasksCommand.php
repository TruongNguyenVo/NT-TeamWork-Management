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
        //task ve lap trinh phan mem
        $tasks_software_development =[
            'room_id' => 2,
            'data' => [
                [
                    'name' => 'Phân tích yêu cầu',
                    'content' => 'Phân tích yêu cầu của khách hàng và xác định các tính năng cần phát triển',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-01',
                    'end_date' => '2023-10-05',
                    'review_date' => '2023-10-07',
                    'finish_date' => '2023-10-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Thiết kế kiến trúc phần mềm',
                    'content' => 'Thiết kế kiến trúc tổng thể cho phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-05',
                    'end_date' => '2023-10-10',
                    'review_date' => '2023-10-12',
                    'finish_date' => '2023-10-15',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Phát triển tính năng chính',
                    'content' => 'Phát triển các tính năng chính của phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-10',
                    'end_date' => '2023-10-20',
                    'review_date' => '2023-10-25',
                    'finish_date' => '2023-10-30',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Kiểm thử phần mềm',
                    'content' => 'Kiểm thử các tính năng của phần mềm để đảm bảo chất lượng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-20',
                    'end_date' => '2023-10-25',
                    'review_date' => '2023-10-28',
                    'finish_date' => '2023-10-30',
                    'status' => 'review'
                ],
                [
                    'name' => 'Triển khai phần mềm',
                    'content' => 'Triển khai phần mềm lên môi trường thực tế',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-25',
                    'end_date' => '2023-10-30',
                    'review_date' => '2023-11-02',
                    'finish_date' => '2023-11-05',
                    'status' => 'done'
                ],
                [
                    'name' => 'Đào tạo người dùng',
                    'content' => 'Đào tạo người dùng sử dụng phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-01',
                    'end_date' => '2023-11-05',
                    'review_date' => '2023-11-07',
                    'finish_date' => '2023-11-10',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Thu thập phản hồi',
                    'content' => 'Thu thập phản hồi từ người dùng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-05',
                    'end_date' => '2023-11-10',
                    'review_date' => '2023-11-12',
                    'finish_date' => '2023-11-15',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Cải tiến phần mềm',
                    'content' => 'Cải tiến phần mềm dựa trên phản hồi',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-10',
                    'end_date' => '2023-11-20',
                    'review_date' => '2023-11-25',
                    'finish_date' => '2023-11-30',
                    'status' => 'review'
                ],
                [
                    'name' => 'Bảo trì phần mềm',
                    'content' => 'Bảo trì phần mềm định kỳ',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-15',
                    'end_date' => '2023-11-25',
                    'review_date' => '2023-11-30',
                    'finish_date' => '2023-12-05',
                    'status' => 'done'
                ],
                [
                    'name' => 'Báo cáo tiến độ',
                    'content' => 'Báo cáo tiến độ phát triển phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-20',
                    'end_date' => '2023-11-30',
                    'review_date' => '2023-12-05',
                    'finish_date' => '2023-12-10',
                    'status' => 'cancelled'
                ],
                [
                    'name' => 'Lập kế hoạch phát triển',
                    'content' => 'Lập kế hoạch phát triển phần mềm chi tiết',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-01',
                    'end_date' => '2023-12-05',
                    'review_date' => '2023-12-07',
                    'finish_date' => '2023-12-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Phân tích thiết kế hệ thống',
                    'content' => 'Phân tích và thiết kế hệ thống chi tiết',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-05',
                    'end_date' => '2023-12-10',
                    'review_date' => '2023-12-12',
                    'finish_date' => '2023-12-15',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Phát triển module A',
                    'content' => 'Phát triển module A của phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-10',
                    'end_date' => '2023-12-20',
                    'review_date' => '2023-12-25',
                    'finish_date' => '2023-12-30',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Phát triển module B',
                    'content' => 'Phát triển module B của phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-15',
                    'end_date' => '2023-12-25',
                    'review_date' => '2023-12-30',
                    'finish_date' => '2024-01-05',
                    'status' => 'review'
                ],
                [
                    'name' => 'Kiểm thử module A',
                    'content' => 'Kiểm thử module A của phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-20',
                    'end_date' => '2023-12-25',
                    'review_date' => '2023-12-28',
                    'finish_date' => '2023-12-30',
                    'status' => 'done'
                ],
                [
                    'name' => 'Kiểm thử module B',
                    'content' => 'Kiểm thử module B của phần mềm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-25',
                    'end_date' => '2023-12-30',
                    'review_date' => '2024-01-02',
                    'finish_date' => '2024-01-05',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Triển khai module A',
                    'content' => 'Triển khai module A lên môi trường thực tế',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-01-05',
                    'review_date' => '2024-01-07',
                    'finish_date' => '2024-01-10',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Triển khai module B',
                    'content' => 'Triển khai module B lên môi trường thực tế',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-05',
                    'end_date' => '2024-01-10',
                    'review_date' => '2024-01-12',
                    'finish_date' => '2024-01-15',
                    'status' => 'review'
                ],
                [
                    'name' => 'Đào tạo người dùng module A',
                    'content' => 'Đào tạo người dùng sử dụng module A',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-10',
                    'end_date' => '2024-01-15',
                    'review_date' => '2024-01-17',
                    'finish_date' => '2024-01-20',
                    'status' => 'done'
                ],
                [
                    'name' => 'Đào tạo người dùng module B',
                    'content' => 'Đào tạo người dùng sử dụng module B',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-15',
                    'end_date' => '2024-01-20',
                    'review_date' => '2024-01-22',
                    'finish_date' => '2024-01-25',
                    'status' => 'pending'
                ]
            ]
        ];
        //task ve thiet ke co so du lieu
        $tasks_database_design = [
            'room_id' => 3,
            'data' =>
                [
                    [
                        'name' => 'Phân tích yêu cầu dữ liệu',
                        'content' => 'Phân tích yêu cầu dữ liệu từ khách hàng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-01',
                        'end_date' => '2023-10-05',
                        'review_date' => '2023-10-07',
                        'finish_date' => '2023-10-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế mô hình ERD',
                        'content' => 'Thiết kế mô hình ERD cho hệ thống',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-05',
                        'end_date' => '2023-10-10',
                        'review_date' => '2023-10-12',
                        'finish_date' => '2023-10-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Xác định các thực thể và thuộc tính',
                        'content' => 'Xác định các thực thể và thuộc tính cần thiết',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-10',
                        'end_date' => '2023-10-15',
                        'review_date' => '2023-10-17',
                        'finish_date' => '2023-10-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế các mối quan hệ',
                        'content' => 'Thiết kế các mối quan hệ giữa các thực thể',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-15',
                        'end_date' => '2023-10-20',
                        'review_date' => '2023-10-22',
                        'finish_date' => '2023-10-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Tạo lược đồ cơ sở dữ liệu',
                        'content' => 'Tạo lược đồ cơ sở dữ liệu từ mô hình ERD',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-20',
                        'end_date' => '2023-10-25',
                        'review_date' => '2023-10-27',
                        'finish_date' => '2023-10-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Xác định các chỉ mục',
                        'content' => 'Xác định các chỉ mục cần thiết cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-25',
                        'end_date' => '2023-10-30',
                        'review_date' => '2023-11-02',
                        'finish_date' => '2023-11-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế các bảng và cột',
                        'content' => 'Thiết kế các bảng và cột cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-05',
                        'review_date' => '2023-11-07',
                        'finish_date' => '2023-11-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Tạo các ràng buộc',
                        'content' => 'Tạo các ràng buộc cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-12',
                        'finish_date' => '2023-11-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Tối ưu hóa truy vấn',
                        'content' => 'Tối ưu hóa các truy vấn cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-17',
                        'finish_date' => '2023-11-20',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế các thủ tục lưu trữ',
                        'content' => 'Thiết kế các thủ tục lưu trữ cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-22',
                        'finish_date' => '2023-11-25',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế các trigger',
                        'content' => 'Thiết kế các trigger cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-27',
                        'finish_date' => '2023-11-30',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế các view',
                        'content' => 'Thiết kế các view cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-02',
                        'finish_date' => '2023-12-05',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế các bảng tạm',
                        'content' => 'Thiết kế các bảng tạm cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-07',
                        'finish_date' => '2023-12-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế các bảng phân vùng',
                        'content' => 'Thiết kế các bảng phân vùng cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-12',
                        'finish_date' => '2023-12-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế các bảng liên kết',
                        'content' => 'Thiết kế các bảng liên kết cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-17',
                        'finish_date' => '2023-12-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế các bảng lịch sử',
                        'content' => 'Thiết kế các bảng lịch sử cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-22',
                        'finish_date' => '2023-12-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế các bảng log',
                        'content' => 'Thiết kế các bảng log cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-27',
                        'finish_date' => '2023-12-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế các bảng cấu hình',
                        'content' => 'Thiết kế các bảng cấu hình cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2023-12-30',
                        'review_date' => '2024-01-02',
                        'finish_date' => '2024-01-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế các bảng tham chiếu',
                        'content' => 'Thiết kế các bảng tham chiếu cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-07',
                        'finish_date' => '2024-01-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế các bảng phân quyền',
                        'content' => 'Thiết kế các bảng phân quyền cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-05',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-12',
                        'finish_date' => '2024-01-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế các bảng cấu trúc',
                        'content' => 'Thiết kế các bảng cấu trúc cho cơ sở dữ liệu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-10',
                        'end_date' => '2024-01-15',
                        'review_date' => '2024-01-17',
                        'finish_date' => '2024-01-20',
                        'status' => 'done'
                    ]
                ]
            
        ];
        //task ve nghien cuu tri tue nhan tao
        $tasks_ai_research = [
            'room_id' => 4,
            'data'=> 
                [
                    [
                        'name' => 'Nghiên cứu thuật toán học sâu',
                        'content' => 'Nghiên cứu và phát triển các thuật toán học sâu mới',
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
                        'name' => 'Phân tích dữ liệu lớn',
                        'content' => 'Phân tích và xử lý dữ liệu lớn để tìm ra các mẫu',
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
                        'name' => 'Phát triển mô hình học máy',
                        'content' => 'Phát triển và huấn luyện các mô hình học máy',
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
                        'name' => 'Tối ưu hóa mô hình AI',
                        'content' => 'Tối ưu hóa các mô hình AI để cải thiện hiệu suất',
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
                        'name' => 'Phân tích cảm xúc',
                        'content' => 'Phát triển hệ thống phân tích cảm xúc từ văn bản',
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
                        'name' => 'Nhận diện hình ảnh',
                        'content' => 'Phát triển hệ thống nhận diện hình ảnh',
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
                        'name' => 'Xử lý ngôn ngữ tự nhiên',
                        'content' => 'Phát triển hệ thống xử lý ngôn ngữ tự nhiên',
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
                        'name' => 'Phát triển chatbot',
                        'content' => 'Phát triển chatbot thông minh',
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
                        'name' => 'Phân tích dữ liệu y tế',
                        'content' => 'Phân tích dữ liệu y tế để dự đoán bệnh',
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
                        'name' => 'Phát triển hệ thống khuyến nghị',
                        'content' => 'Phát triển hệ thống khuyến nghị sản phẩm',
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
                        'name' => 'Phân tích dữ liệu tài chính',
                        'content' => 'Phân tích dữ liệu tài chính để dự đoán xu hướng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-28',
                        'end_date' => '2023-11-08',
                        'review_date' => '2023-11-13',
                        'finish_date' => '2023-11-18',
                        'status' => 'cancelled'
                    ],
                    [
                        'name' => 'Phát triển hệ thống tự động hóa',
                        'content' => 'Phát triển hệ thống tự động hóa quy trình',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-15',
                        'finish_date' => '2023-11-20',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu hình ảnh y tế',
                        'content' => 'Phân tích dữ liệu hình ảnh y tế để chẩn đoán bệnh',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-20',
                        'finish_date' => '2023-11-25',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phát triển hệ thống dự đoán thời tiết',
                        'content' => 'Phát triển hệ thống dự đoán thời tiết',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-25',
                        'finish_date' => '2023-11-30',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu giao thông',
                        'content' => 'Phân tích dữ liệu giao thông để tối ưu hóa lộ trình',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-30',
                        'finish_date' => '2023-12-05',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển hệ thống nhận diện giọng nói',
                        'content' => 'Phát triển hệ thống nhận diện giọng nói',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-05',
                        'finish_date' => '2023-12-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu thị trường',
                        'content' => 'Phân tích dữ liệu thị trường để dự đoán xu hướng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-10',
                        'finish_date' => '2023-12-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phát triển hệ thống phát hiện gian lận',
                        'content' => 'Phát triển hệ thống phát hiện gian lận trong giao dịch',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-15',
                        'finish_date' => '2023-12-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu mạng xã hội',
                        'content' => 'Phân tích dữ liệu mạng xã hội để tìm ra các xu hướng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-20',
                        'finish_date' => '2023-12-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển hệ thống dự đoán nhu cầu',
                        'content' => 'Phát triển hệ thống dự đoán nhu cầu sản phẩm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-25',
                        'finish_date' => '2023-12-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu sinh học',
                        'content' => 'Phân tích dữ liệu sinh học để tìm ra các mẫu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-30',
                        'finish_date' => '2024-01-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển hệ thống nhận diện khuôn mặt',
                        'content' => 'Phát triển hệ thống nhận diện khuôn mặt',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-30',
                        'review_date' => '2024-01-05',
                        'finish_date' => '2024-01-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phân tích dữ liệu hành vi người dùng',
                        'content' => 'Phân tích dữ liệu hành vi người dùng để tối ưu hóa trải nghiệm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-10',
                        'finish_date' => '2024-01-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển hệ thống dự đoán doanh thu',
                        'content' => 'Phát triển hệ thống dự đoán doanh thu',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-15',
                        'finish_date' => '2024-01-20',
                        'status' => 'done'
                    ]
                ]
            
        ];
        //task ve thiet ke UX/UI
        $tasks_UX_UI_design = [
            'room_id'=> 5,
            'data' => 
                [
                    [
                        'name' => 'Nghiên cứu người dùng',
                        'content' => 'Thu thập và phân tích dữ liệu người dùng để hiểu nhu cầu và hành vi của họ',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-01',
                        'end_date' => '2023-10-05',
                        'review_date' => '2023-10-07',
                        'finish_date' => '2023-10-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Tạo wireframe',
                        'content' => 'Tạo wireframe cho các trang chính của ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-05',
                        'end_date' => '2023-10-10',
                        'review_date' => '2023-10-12',
                        'finish_date' => '2023-10-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện người dùng',
                        'content' => 'Thiết kế giao diện người dùng cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-10',
                        'end_date' => '2023-10-20',
                        'review_date' => '2023-10-25',
                        'finish_date' => '2023-10-30',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Tạo prototype',
                        'content' => 'Tạo prototype tương tác cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-20',
                        'end_date' => '2023-10-25',
                        'review_date' => '2023-10-28',
                        'finish_date' => '2023-10-30',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Kiểm thử người dùng',
                        'content' => 'Thực hiện kiểm thử người dùng với prototype',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-25',
                        'end_date' => '2023-10-30',
                        'review_date' => '2023-11-02',
                        'finish_date' => '2023-11-05',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích kết quả kiểm thử',
                        'content' => 'Phân tích kết quả kiểm thử người dùng và đưa ra cải tiến',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-05',
                        'review_date' => '2023-11-07',
                        'finish_date' => '2023-11-10',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Cải tiến thiết kế',
                        'content' => 'Cải tiến thiết kế dựa trên phản hồi từ kiểm thử người dùng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-12',
                        'finish_date' => '2023-11-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống màu sắc',
                        'content' => 'Thiết kế hệ thống màu sắc cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-17',
                        'finish_date' => '2023-11-20',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống biểu tượng',
                        'content' => 'Thiết kế hệ thống biểu tượng cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-22',
                        'finish_date' => '2023-11-25',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống typography',
                        'content' => 'Thiết kế hệ thống typography cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-27',
                        'finish_date' => '2023-11-30',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống layout',
                        'content' => 'Thiết kế hệ thống layout cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-02',
                        'finish_date' => '2023-12-05',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống điều hướng',
                        'content' => 'Thiết kế hệ thống điều hướng cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-07',
                        'finish_date' => '2023-12-10',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống tương tác',
                        'content' => 'Thiết kế hệ thống tương tác cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-12',
                        'finish_date' => '2023-12-15',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống phản hồi',
                        'content' => 'Thiết kế hệ thống phản hồi cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-17',
                        'finish_date' => '2023-12-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống thông báo',
                        'content' => 'Thiết kế hệ thống thông báo cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-22',
                        'finish_date' => '2023-12-25',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống đăng nhập',
                        'content' => 'Thiết kế hệ thống đăng nhập cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-27',
                        'finish_date' => '2023-12-30',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống đăng ký',
                        'content' => 'Thiết kế hệ thống đăng ký cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-10',
                        'finish_date' => '2024-01-15',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống quản lý tài khoản',
                        'content' => 'Thiết kế hệ thống quản lý tài khoản cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-15',
                        'finish_date' => '2024-01-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống quản lý nội dung',
                        'content' => 'Thiết kế hệ thống quản lý nội dung cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-05',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-12',
                        'finish_date' => '2024-01-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống quản lý thông báo',
                        'content' => 'Thiết kế hệ thống quản lý thông báo cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-10',
                        'end_date' => '2024-01-15',
                        'review_date' => '2024-01-17',
                        'finish_date' => '2024-01-20',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống quản lý quyền',
                        'content' => 'Thiết kế hệ thống quản lý quyền cho ứng dụng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-15',
                        'end_date' => '2024-01-20',
                        'review_date' => '2024-01-22',
                        'finish_date' => '2024-01-25',
                        'status' => 'done'
                    ]
                ]
        ];
        //task ve an toan thong tin
        $tasks_information_security = [
            'room_id' => 6,
            'data' => 
                [
                    [
                        'name' => 'Đánh giá rủi ro bảo mật',
                        'content' => 'Đánh giá các rủi ro bảo mật tiềm ẩn trong hệ thống',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-01',
                        'end_date' => '2023-10-05',
                        'review_date' => '2023-10-07',
                        'finish_date' => '2023-10-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích lỗ hổng bảo mật',
                        'content' => 'Phân tích các lỗ hổng bảo mật trong hệ thống',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-05',
                        'end_date' => '2023-10-10',
                        'review_date' => '2023-10-12',
                        'finish_date' => '2023-10-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế chính sách bảo mật',
                        'content' => 'Thiết kế các chính sách bảo mật cho hệ thống',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-10',
                        'end_date' => '2023-10-15',
                        'review_date' => '2023-10-17',
                        'finish_date' => '2023-10-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Triển khai hệ thống bảo mật',
                        'content' => 'Triển khai các biện pháp bảo mật cho hệ thống',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-15',
                        'end_date' => '2023-10-20',
                        'review_date' => '2023-10-22',
                        'finish_date' => '2023-10-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Kiểm thử bảo mật hệ thống',
                        'content' => 'Kiểm thử các biện pháp bảo mật đã triển khai',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-20',
                        'end_date' => '2023-10-25',
                        'review_date' => '2023-10-27',
                        'finish_date' => '2023-10-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Đào tạo nhân viên về bảo mật',
                        'content' => 'Đào tạo nhân viên về các biện pháp bảo mật',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-25',
                        'end_date' => '2023-10-30',
                        'review_date' => '2023-11-02',
                        'finish_date' => '2023-11-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phân tích sự cố bảo mật',
                        'content' => 'Phân tích các sự cố bảo mật đã xảy ra',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-05',
                        'review_date' => '2023-11-07',
                        'finish_date' => '2023-11-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Cải tiến hệ thống bảo mật',
                        'content' => 'Cải tiến các biện pháp bảo mật dựa trên phân tích sự cố',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-12',
                        'finish_date' => '2023-11-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống giám sát bảo mật',
                        'content' => 'Thiết kế hệ thống giám sát bảo mật liên tục',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-17',
                        'finish_date' => '2023-11-20',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Triển khai hệ thống giám sát bảo mật',
                        'content' => 'Triển khai hệ thống giám sát bảo mật liên tục',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-22',
                        'finish_date' => '2023-11-25',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phân tích log bảo mật',
                        'content' => 'Phân tích log bảo mật để phát hiện các hoạt động bất thường',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-27',
                        'finish_date' => '2023-11-30',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống phản ứng sự cố',
                        'content' => 'Thiết kế hệ thống phản ứng nhanh khi xảy ra sự cố bảo mật',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-02',
                        'finish_date' => '2023-12-05',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Triển khai hệ thống phản ứng sự cố',
                        'content' => 'Triển khai hệ thống phản ứng nhanh khi xảy ra sự cố bảo mật',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-07',
                        'finish_date' => '2023-12-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Đánh giá lại hệ thống bảo mật',
                        'content' => 'Đánh giá lại toàn bộ hệ thống bảo mật sau khi triển khai',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-12',
                        'finish_date' => '2023-12-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phân tích xu hướng bảo mật',
                        'content' => 'Phân tích các xu hướng bảo mật mới nhất',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-17',
                        'finish_date' => '2023-12-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Cập nhật hệ thống bảo mật',
                        'content' => 'Cập nhật hệ thống bảo mật theo các xu hướng mới',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-22',
                        'finish_date' => '2023-12-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Đào tạo nâng cao về bảo mật',
                        'content' => 'Đào tạo nâng cao về các biện pháp bảo mật mới',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-27',
                        'finish_date' => '2023-12-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phân tích bảo mật mạng',
                        'content' => 'Phân tích bảo mật mạng để phát hiện các lỗ hổng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-10',
                        'finish_date' => '2024-01-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Thiết kế hệ thống bảo mật mạng',
                        'content' => 'Thiết kế hệ thống bảo mật mạng để ngăn chặn các cuộc tấn công',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-15',
                        'finish_date' => '2024-01-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Triển khai hệ thống bảo mật mạng',
                        'content' => 'Triển khai hệ thống bảo mật mạng để ngăn chặn các cuộc tấn công',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-05',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-12',
                        'finish_date' => '2024-01-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Kiểm thử bảo mật mạng',
                        'content' => 'Kiểm thử hệ thống bảo mật mạng để đảm bảo hiệu quả',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-10',
                        'end_date' => '2024-01-15',
                        'review_date' => '2024-01-17',
                        'finish_date' => '2024-01-20',
                        'status' => 'done'
                    ]
                ]
            
        ];
        //task ve phat trien web
        $tasks_web_development = [
            'room_id' => 7,
            'data' => 
                [
                    [
                        'name' => 'Thiết kế giao diện trang chủ',
                        'content' => 'Thiết kế giao diện người dùng cho trang chủ của website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-01',
                        'end_date' => '2023-10-05',
                        'review_date' => '2023-10-07',
                        'finish_date' => '2023-10-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phát triển chức năng đăng nhập',
                        'content' => 'Phát triển chức năng đăng nhập cho người dùng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-05',
                        'end_date' => '2023-10-10',
                        'review_date' => '2023-10-12',
                        'finish_date' => '2023-10-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Tạo cơ sở dữ liệu người dùng',
                        'content' => 'Tạo cơ sở dữ liệu để lưu trữ thông tin người dùng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-10',
                        'end_date' => '2023-10-15',
                        'review_date' => '2023-10-17',
                        'finish_date' => '2023-10-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển chức năng đăng ký',
                        'content' => 'Phát triển chức năng đăng ký tài khoản cho người dùng mới',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-15',
                        'end_date' => '2023-10-20',
                        'review_date' => '2023-10-22',
                        'finish_date' => '2023-10-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang sản phẩm',
                        'content' => 'Thiết kế giao diện người dùng cho trang sản phẩm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-20',
                        'end_date' => '2023-10-25',
                        'review_date' => '2023-10-27',
                        'finish_date' => '2023-10-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phát triển chức năng giỏ hàng',
                        'content' => 'Phát triển chức năng giỏ hàng cho người dùng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-25',
                        'end_date' => '2023-10-30',
                        'review_date' => '2023-11-02',
                        'finish_date' => '2023-11-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Tích hợp thanh toán trực tuyến',
                        'content' => 'Tích hợp các phương thức thanh toán trực tuyến vào website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-05',
                        'review_date' => '2023-11-07',
                        'finish_date' => '2023-11-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phát triển chức năng tìm kiếm',
                        'content' => 'Phát triển chức năng tìm kiếm sản phẩm trên website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-12',
                        'finish_date' => '2023-11-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang liên hệ',
                        'content' => 'Thiết kế giao diện người dùng cho trang liên hệ',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-17',
                        'finish_date' => '2023-11-20',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phát triển chức năng bình luận',
                        'content' => 'Phát triển chức năng bình luận cho sản phẩm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-22',
                        'finish_date' => '2023-11-25',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Tối ưu hóa tốc độ tải trang',
                        'content' => 'Tối ưu hóa tốc độ tải trang của website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-27',
                        'finish_date' => '2023-11-30',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang blog',
                        'content' => 'Thiết kế giao diện người dùng cho trang blog',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-02',
                        'finish_date' => '2023-12-05',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý bài viết',
                        'content' => 'Phát triển chức năng quản lý bài viết cho trang blog',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-07',
                        'finish_date' => '2023-12-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Tích hợp API mạng xã hội',
                        'content' => 'Tích hợp API của các mạng xã hội vào website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-12',
                        'finish_date' => '2023-12-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý người dùng',
                        'content' => 'Phát triển chức năng quản lý người dùng cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-17',
                        'finish_date' => '2023-12-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang quản trị',
                        'content' => 'Thiết kế giao diện người dùng cho trang quản trị',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-22',
                        'finish_date' => '2023-12-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý sản phẩm',
                        'content' => 'Phát triển chức năng quản lý sản phẩm cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-27',
                        'finish_date' => '2023-12-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Tối ưu hóa SEO',
                        'content' => 'Tối ưu hóa SEO cho website',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-10',
                        'finish_date' => '2024-01-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý đơn hàng',
                        'content' => 'Phát triển chức năng quản lý đơn hàng cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-15',
                        'finish_date' => '2024-01-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang FAQ',
                        'content' => 'Thiết kế giao diện người dùng cho trang FAQ',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-05',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-12',
                        'finish_date' => '2024-01-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý bình luận',
                        'content' => 'Phát triển chức năng quản lý bình luận cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-10',
                        'end_date' => '2024-01-15',
                        'review_date' => '2024-01-17',
                        'finish_date' => '2024-01-20',
                        'status' => 'done'
                    ]
                ]

        ];
        //task ve lap trinh ung dung di dong
        $tasks_mobile_app_development = [
            'room_id'=> 8,
            'data' => 
                [
                    [
                        'name' => 'Phân tích yêu cầu ứng dụng',
                        'content' => 'Phân tích yêu cầu của khách hàng và xác định các tính năng cần phát triển',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-01',
                        'end_date' => '2023-10-05',
                        'review_date' => '2023-10-07',
                        'finish_date' => '2023-10-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Thiết kế giao diện người dùng',
                        'content' => 'Thiết kế giao diện người dùng cho ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-05',
                        'end_date' => '2023-10-10',
                        'review_date' => '2023-10-12',
                        'finish_date' => '2023-10-15',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phát triển tính năng đăng nhập',
                        'content' => 'Phát triển tính năng đăng nhập cho ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-10',
                        'end_date' => '2023-10-15',
                        'review_date' => '2023-10-17',
                        'finish_date' => '2023-10-20',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển tính năng đăng ký',
                        'content' => 'Phát triển tính năng đăng ký tài khoản cho ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-15',
                        'end_date' => '2023-10-20',
                        'review_date' => '2023-10-22',
                        'finish_date' => '2023-10-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Tạo cơ sở dữ liệu người dùng',
                        'content' => 'Tạo cơ sở dữ liệu để lưu trữ thông tin người dùng',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-20',
                        'end_date' => '2023-10-25',
                        'review_date' => '2023-10-27',
                        'finish_date' => '2023-10-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phát triển tính năng giỏ hàng',
                        'content' => 'Phát triển tính năng giỏ hàng cho ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-10-25',
                        'end_date' => '2023-10-30',
                        'review_date' => '2023-11-02',
                        'finish_date' => '2023-11-05',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Tích hợp thanh toán trực tuyến',
                        'content' => 'Tích hợp các phương thức thanh toán trực tuyến vào ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-01',
                        'end_date' => '2023-11-05',
                        'review_date' => '2023-11-07',
                        'finish_date' => '2023-11-10',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Phát triển tính năng tìm kiếm',
                        'content' => 'Phát triển tính năng tìm kiếm sản phẩm trên ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-05',
                        'end_date' => '2023-11-10',
                        'review_date' => '2023-11-12',
                        'finish_date' => '2023-11-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang sản phẩm',
                        'content' => 'Thiết kế giao diện người dùng cho trang sản phẩm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-10',
                        'end_date' => '2023-11-15',
                        'review_date' => '2023-11-17',
                        'finish_date' => '2023-11-20',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Phát triển tính năng bình luận',
                        'content' => 'Phát triển tính năng bình luận cho sản phẩm',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-15',
                        'end_date' => '2023-11-20',
                        'review_date' => '2023-11-22',
                        'finish_date' => '2023-11-25',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Tối ưu hóa tốc độ tải trang',
                        'content' => 'Tối ưu hóa tốc độ tải trang của ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-20',
                        'end_date' => '2023-11-25',
                        'review_date' => '2023-11-27',
                        'finish_date' => '2023-11-30',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang liên hệ',
                        'content' => 'Thiết kế giao diện người dùng cho trang liên hệ',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-11-25',
                        'end_date' => '2023-11-30',
                        'review_date' => '2023-12-02',
                        'finish_date' => '2023-12-05',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý bài viết',
                        'content' => 'Phát triển chức năng quản lý bài viết cho trang blog',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-01',
                        'end_date' => '2023-12-05',
                        'review_date' => '2023-12-07',
                        'finish_date' => '2023-12-10',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Tích hợp API mạng xã hội',
                        'content' => 'Tích hợp API của các mạng xã hội vào ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-05',
                        'end_date' => '2023-12-10',
                        'review_date' => '2023-12-12',
                        'finish_date' => '2023-12-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý người dùng',
                        'content' => 'Phát triển chức năng quản lý người dùng cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-10',
                        'end_date' => '2023-12-15',
                        'review_date' => '2023-12-17',
                        'finish_date' => '2023-12-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang quản trị',
                        'content' => 'Thiết kế giao diện người dùng cho trang quản trị',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-15',
                        'end_date' => '2023-12-20',
                        'review_date' => '2023-12-22',
                        'finish_date' => '2023-12-25',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý sản phẩm',
                        'content' => 'Phát triển chức năng quản lý sản phẩm cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-20',
                        'end_date' => '2023-12-25',
                        'review_date' => '2023-12-27',
                        'finish_date' => '2023-12-30',
                        'status' => 'done'
                    ],
                    [
                        'name' => 'Tối ưu hóa SEO',
                        'content' => 'Tối ưu hóa SEO cho ứng dụng di động',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2023-12-25',
                        'end_date' => '2024-01-05',
                        'review_date' => '2024-01-10',
                        'finish_date' => '2024-01-15',
                        'status' => 'pending'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý đơn hàng',
                        'content' => 'Phát triển chức năng quản lý đơn hàng cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-15',
                        'finish_date' => '2024-01-20',
                        'status' => 'in_progress'
                    ],
                    [
                        'name' => 'Thiết kế giao diện trang FAQ',
                        'content' => 'Thiết kế giao diện người dùng cho trang FAQ',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-05',
                        'end_date' => '2024-01-10',
                        'review_date' => '2024-01-12',
                        'finish_date' => '2024-01-15',
                        'status' => 'review'
                    ],
                    [
                        'name' => 'Phát triển chức năng quản lý bình luận',
                        'content' => 'Phát triển chức năng quản lý bình luận cho admin',
                        'path_attachment' => '',
                        'result_content' => '',
                        'result_attachment' => '',
                        'start_date' => '2024-01-10',
                        'end_date' => '2024-01-15',
                        'review_date' => '2024-01-17',
                        'finish_date' => '2024-01-20',
                        'status' => 'done'
                    ]
                ]
            
        ];
        //task ve do an tot nghiep
        $tasks_final_project = [
            'room_id'=> 9,
            'data' => [
                [
                    'name' => 'Lập kế hoạch đồ án',
                    'content' => 'Lập kế hoạch chi tiết cho đồ án tốt nghiệp',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-01',
                    'end_date' => '2023-10-05',
                    'review_date' => '2023-10-07',
                    'finish_date' => '2023-10-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Phân tích yêu cầu',
                    'content' => 'Phân tích yêu cầu của đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-05',
                    'end_date' => '2023-10-10',
                    'review_date' => '2023-10-12',
                    'finish_date' => '2023-10-15',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Thiết kế hệ thống',
                    'content' => 'Thiết kế hệ thống cho đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-10',
                    'end_date' => '2023-10-15',
                    'review_date' => '2023-10-17',
                    'finish_date' => '2023-10-20',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Phát triển module A',
                    'content' => 'Phát triển module A của hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-15',
                    'end_date' => '2023-10-20',
                    'review_date' => '2023-10-22',
                    'finish_date' => '2023-10-25',
                    'status' => 'review'
                ],
                [
                    'name' => 'Phát triển module B',
                    'content' => 'Phát triển module B của hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-20',
                    'end_date' => '2023-10-25',
                    'review_date' => '2023-10-27',
                    'finish_date' => '2023-10-30',
                    'status' => 'done'
                ],
                [
                    'name' => 'Kiểm thử module A',
                    'content' => 'Kiểm thử module A của hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-25',
                    'end_date' => '2023-10-30',
                    'review_date' => '2023-11-02',
                    'finish_date' => '2023-11-05',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Kiểm thử module B',
                    'content' => 'Kiểm thử module B của hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-01',
                    'end_date' => '2023-11-05',
                    'review_date' => '2023-11-07',
                    'finish_date' => '2023-11-10',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Tích hợp hệ thống',
                    'content' => 'Tích hợp các module vào hệ thống hoàn chỉnh',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-05',
                    'end_date' => '2023-11-10',
                    'review_date' => '2023-11-12',
                    'finish_date' => '2023-11-15',
                    'status' => 'review'
                ],
                [
                    'name' => 'Kiểm thử hệ thống',
                    'content' => 'Kiểm thử toàn bộ hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-10',
                    'end_date' => '2023-11-15',
                    'review_date' => '2023-11-17',
                    'finish_date' => '2023-11-20',
                    'status' => 'done'
                ],
                [
                    'name' => 'Viết tài liệu hướng dẫn',
                    'content' => 'Viết tài liệu hướng dẫn sử dụng hệ thống',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-15',
                    'end_date' => '2023-11-20',
                    'review_date' => '2023-11-22',
                    'finish_date' => '2023-11-25',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Chuẩn bị báo cáo',
                    'content' => 'Chuẩn bị báo cáo đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-20',
                    'end_date' => '2023-11-25',
                    'review_date' => '2023-11-27',
                    'finish_date' => '2023-11-30',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Thuyết trình đồ án',
                    'content' => 'Chuẩn bị thuyết trình đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-25',
                    'end_date' => '2023-11-30',
                    'review_date' => '2023-12-02',
                    'finish_date' => '2023-12-05',
                    'status' => 'review'
                ],
                [
                    'name' => 'Phản biện đồ án',
                    'content' => 'Chuẩn bị phản biện đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-01',
                    'end_date' => '2023-12-05',
                    'review_date' => '2023-12-07',
                    'finish_date' => '2023-12-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Chỉnh sửa đồ án',
                    'content' => 'Chỉnh sửa đồ án theo góp ý',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-05',
                    'end_date' => '2023-12-10',
                    'review_date' => '2023-12-12',
                    'finish_date' => '2023-12-15',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Nộp đồ án',
                    'content' => 'Nộp đồ án hoàn chỉnh',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-10',
                    'end_date' => '2023-12-15',
                    'review_date' => '2023-12-17',
                    'finish_date' => '2023-12-20',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Chuẩn bị tài liệu tham khảo',
                    'content' => 'Chuẩn bị tài liệu tham khảo cho đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-15',
                    'end_date' => '2023-12-20',
                    'review_date' => '2023-12-22',
                    'finish_date' => '2023-12-25',
                    'status' => 'review'
                ],
                [
                    'name' => 'Đánh giá kết quả',
                    'content' => 'Đánh giá kết quả thực hiện đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-20',
                    'end_date' => '2023-12-25',
                    'review_date' => '2023-12-27',
                    'finish_date' => '2023-12-30',
                    'status' => 'done'
                ],
                [
                    'name' => 'Chuẩn bị tài liệu bảo vệ',
                    'content' => 'Chuẩn bị tài liệu bảo vệ đồ án',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-25',
                    'end_date' => '2024-01-05',
                    'review_date' => '2024-01-10',
                    'finish_date' => '2024-01-15',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Bảo vệ đồ án',
                    'content' => 'Bảo vệ đồ án trước hội đồng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-01-10',
                    'review_date' => '2024-01-15',
                    'finish_date' => '2024-01-20',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Hoàn thiện đồ án',
                    'content' => 'Hoàn thiện đồ án sau bảo vệ',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-05',
                    'end_date' => '2024-01-10',
                    'review_date' => '2024-01-12',
                    'finish_date' => '2024-01-15',
                    'status' => 'review'
                ],
                [
                    'name' => 'Nộp đồ án cuối cùng',
                    'content' => 'Nộp đồ án cuối cùng sau khi hoàn thiện',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-10',
                    'end_date' => '2024-01-15',
                    'review_date' => '2024-01-17',
                    'finish_date' => '2024-01-20',
                    'status' => 'done'
                ]
            ]
        ];
        //task ve khoi nghiep cong nghe
        $tasks_technology_startup = [
            'room_id' => 10,

            'data' => [
                [
                    'name' => 'Nghiên cứu thị trường',
                    'content' => 'Phân tích thị trường và xác định nhu cầu khách hàng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-01',
                    'end_date' => '2023-10-05',
                    'review_date' => '2023-10-07',
                    'finish_date' => '2023-10-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Lập kế hoạch kinh doanh',
                    'content' => 'Xây dựng kế hoạch kinh doanh chi tiết',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-05',
                    'end_date' => '2023-10-10',
                    'review_date' => '2023-10-12',
                    'finish_date' => '2023-10-15',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Thiết kế sản phẩm',
                    'content' => 'Thiết kế sản phẩm công nghệ mới',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-10',
                    'end_date' => '2023-10-15',
                    'review_date' => '2023-10-17',
                    'finish_date' => '2023-10-20',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Phát triển sản phẩm',
                    'content' => 'Phát triển sản phẩm công nghệ theo thiết kế',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-15',
                    'end_date' => '2023-10-20',
                    'review_date' => '2023-10-22',
                    'finish_date' => '2023-10-25',
                    'status' => 'review'
                ],
                [
                    'name' => 'Kiểm thử sản phẩm',
                    'content' => 'Kiểm thử sản phẩm để đảm bảo chất lượng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-20',
                    'end_date' => '2023-10-25',
                    'review_date' => '2023-10-27',
                    'finish_date' => '2023-10-30',
                    'status' => 'done'
                ],
                [
                    'name' => 'Phát triển chiến lược marketing',
                    'content' => 'Xây dựng chiến lược marketing cho sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-10-25',
                    'end_date' => '2023-10-30',
                    'review_date' => '2023-11-02',
                    'finish_date' => '2023-11-05',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Tạo website công ty',
                    'content' => 'Thiết kế và phát triển website cho công ty',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-01',
                    'end_date' => '2023-11-05',
                    'review_date' => '2023-11-07',
                    'finish_date' => '2023-11-10',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Tạo nội dung quảng cáo',
                    'content' => 'Tạo nội dung quảng cáo cho sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-05',
                    'end_date' => '2023-11-10',
                    'review_date' => '2023-11-12',
                    'finish_date' => '2023-11-15',
                    'status' => 'review'
                ],
                [
                    'name' => 'Tổ chức sự kiện ra mắt sản phẩm',
                    'content' => 'Lên kế hoạch và tổ chức sự kiện ra mắt sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-10',
                    'end_date' => '2023-11-15',
                    'review_date' => '2023-11-17',
                    'finish_date' => '2023-11-20',
                    'status' => 'done'
                ],
                [
                    'name' => 'Phát triển kênh bán hàng trực tuyến',
                    'content' => 'Xây dựng và phát triển kênh bán hàng trực tuyến',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-15',
                    'end_date' => '2023-11-20',
                    'review_date' => '2023-11-22',
                    'finish_date' => '2023-11-25',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Tuyển dụng nhân sự',
                    'content' => 'Tuyển dụng nhân sự cho công ty',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-20',
                    'end_date' => '2023-11-25',
                    'review_date' => '2023-11-27',
                    'finish_date' => '2023-11-30',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Đào tạo nhân viên',
                    'content' => 'Đào tạo nhân viên về sản phẩm và quy trình làm việc',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-11-25',
                    'end_date' => '2023-11-30',
                    'review_date' => '2023-12-02',
                    'finish_date' => '2023-12-05',
                    'status' => 'review'
                ],
                [
                    'name' => 'Phát triển ứng dụng di động',
                    'content' => 'Phát triển ứng dụng di động cho sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-01',
                    'end_date' => '2023-12-05',
                    'review_date' => '2023-12-07',
                    'finish_date' => '2023-12-10',
                    'status' => 'done'
                ],
                [
                    'name' => 'Tối ưu hóa SEO',
                    'content' => 'Tối ưu hóa SEO cho website công ty',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-05',
                    'end_date' => '2023-12-10',
                    'review_date' => '2023-12-12',
                    'finish_date' => '2023-12-15',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Phát triển chiến lược bán hàng',
                    'content' => 'Xây dựng chiến lược bán hàng cho sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-10',
                    'end_date' => '2023-12-15',
                    'review_date' => '2023-12-17',
                    'finish_date' => '2023-12-20',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Phân tích dữ liệu khách hàng',
                    'content' => 'Phân tích dữ liệu khách hàng để cải thiện sản phẩm',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-15',
                    'end_date' => '2023-12-20',
                    'review_date' => '2023-12-22',
                    'finish_date' => '2023-12-25',
                    'status' => 'review'
                ],
                [
                    'name' => 'Phát triển hệ thống CRM',
                    'content' => 'Phát triển hệ thống quản lý quan hệ khách hàng (CRM)',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-20',
                    'end_date' => '2023-12-25',
                    'review_date' => '2023-12-27',
                    'finish_date' => '2023-12-30',
                    'status' => 'done'
                ],
                [
                    'name' => 'Tạo chiến dịch email marketing',
                    'content' => 'Tạo chiến dịch email marketing để tiếp cận khách hàng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2023-12-25',
                    'end_date' => '2024-01-05',
                    'review_date' => '2024-01-10',
                    'finish_date' => '2024-01-15',
                    'status' => 'pending'
                ],
                [
                    'name' => 'Phát triển hệ thống hỗ trợ khách hàng',
                    'content' => 'Phát triển hệ thống hỗ trợ khách hàng trực tuyến',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-01-10',
                    'review_date' => '2024-01-15',
                    'finish_date' => '2024-01-20',
                    'status' => 'in_progress'
                ],
                [
                    'name' => 'Tạo chiến dịch quảng cáo trên mạng xã hội',
                    'content' => 'Tạo chiến dịch quảng cáo trên các mạng xã hội',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-05',
                    'end_date' => '2024-01-10',
                    'review_date' => '2024-01-12',
                    'finish_date' => '2024-01-15',
                    'status' => 'review'
                ],
                [
                    'name' => 'Phát triển hệ thống quản lý kho',
                    'content' => 'Phát triển hệ thống quản lý kho hàng',
                    'path_attachment' => '',
                    'result_content' => '',
                    'result_attachment' => '',
                    'start_date' => '2024-01-10',
                    'end_date' => '2024-01-15',
                    'review_date' => '2024-01-17',
                    'finish_date' => '2024-01-20',
                    'status' => 'done'
                ]
            ]
        ];

        $tasksAndRoom = [
            $tasks_manage_project, 
            $tasks_software_development, 
            $tasks_database_design,
            $tasks_ai_research,
            $tasks_UX_UI_design,
            $tasks_information_security,
            $tasks_web_development,
            $tasks_mobile_app_development,
            $tasks_final_project,
            $tasks_technology_startup
        ];

        //duyet qua tat ca cac tasks
        foreach($tasksAndRoom as $taskAndRoom){
            $room = $this->entityManager->getRepository(Room::class)->find($taskAndRoom['room_id']);
            // lay danh sach tat ca thanh vien cua nhom room_1
            $members_data = $this->entityManager->getRepository(UserRoom::class)->findBy(['room' => $room,'status' =>'joined', 'role' => 'member']);
            $admin = $this->entityManager->getRepository(UserRoom::class)->findOneBy(['room'=> $room,'role'=> 'admin','status'=> 'joined']);
            foreach($taskAndRoom['data'] as $task){
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
                $this->entityManager->flush();
                $output->writeln('Da tao task voi:'. $new_task->getId() .'Ten cong viec:'.$new_task->getName());
            }
        }
        
        $output->writeln("'Seeding complete!'");
        return Command::SUCCESS;

    }
}
