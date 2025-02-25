<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use App\Entity\User;

#[AsCommand(
    name: 'app:seed-users',
    description: 'Add a short description for your command',
)]
class SeedUsersCommand extends Command
{
    protected static $defaultName = 'app:seed-users';
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
        $faker = FakerFactory::create('vi_VN');
         
        //defalut user
        $email_edu = new User();
        $email_edu->setFirstname('Nguyên');
        $email_edu->setLastname('Võ Trường');
        $email_edu->setEmail('vtnguyenhttt2211025@student.ctuet.edu.vn');

        $email_personal = new User();
        $email_personal->setFirstname('Nguyên');
        $email_personal->setLastname('Trường');
        $email_personal->setEmail('vonguyen.0407@gmail.com');

        $email_personal_1 = new User();
        $email_personal_1->setFirstname('Nguyên');
        $email_personal_1->setLastname('Võ');
        $email_personal_1->setEmail('vonguyen@gmail.com');
        $email_personal_1->setPassword(password_hash('123456', PASSWORD_BCRYPT));

        $this->entityManager->persist($email_edu);
        $this->entityManager->persist($email_personal);
        $this->entityManager->persist($email_personal_1);


        for ($i = 1; $i <= 8; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->unique()->email);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT)); // Example hashed password
            
            $this->entityManager->persist($user);

            $output->writeln("Created user: {$user->getFirstname()} {$user->getLastname()}");
        }

        $this->entityManager->flush();
        $output->writeln('Seeding complete!');

        return Command::SUCCESS;
    }
}
