<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        parent::__construct($registry, Task::class);
    }

    //HAM TAO TASK
    // public function createTask($room, $task): Task
    // {
    //     try {
    //         $this->entityManager->beginTransaction();
    //         $newTask = new Task();
    //         $newTask->setRoom($room);
    //         $newTask->setName($task->getName());
    //         $newTask->setStartDate($task->getStartDate());
    //         $newTask->setEndDate($task->getEndDate() ?? null);
    //         $newTask->setContent(json_encode($task->getContent()));
            

    //     } catch (\Exception $exception) {
    //         dump("Error in task repository" ,$exception->getMessage());
    //         die();
    //     }
    // }
    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
