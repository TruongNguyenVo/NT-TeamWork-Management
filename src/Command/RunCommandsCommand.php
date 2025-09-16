<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'RunCommands',
    description: 'Add a short description for your command',
)]
class RunCommandsCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:run-commands')
            ->setDescription('Chạy lệnh tùy chỉnh.');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = [
            'app:seed-users',
            'app:seed-rooms',
            'app:seed-userRooms',
            'app:seed-tasks',
        ];


        foreach ($seeders as $seederCommand) {
            $output->writeln("<info>Đang chạy: {$seederCommand}</info>");
            
            // Chạy command Symfony
            $process = new Process(['php', 'bin/console', $seederCommand]);
            $process->run();

            // Hiển thị output của từng Seeder
            if ($process->isSuccessful()) {
                $output->writeln("<info>{$seederCommand} đã chạy thành công!</info>");
            } else {
                $output->writeln("<error>Lỗi khi chạy {$seederCommand}: " . $process->getErrorOutput() . "</error>");
            }
        }

        return Command::SUCCESS;
    }
}
