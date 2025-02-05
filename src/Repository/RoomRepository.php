<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\UserRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Room::class);
    }

    //    /**
    //     * @return Room[] Returns an array of Room objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    public function createRoom($room, $user)
    {
        //viet transaction, mac dinh nguoi tao phong la admin
        try{
            $entityManager->beginTransaction();
            $room->setCreateDate(new \DateTime()); //set ngay tao phong
            $userGroup = new UserRoom();
            // dump("toidayroi");
            // die();
            $userGroup->setUser($user);
            $userGroup->setRoom($room);
            $userGroup->setRole("admin");
            $userGroup->setStatus("joined");
            $entityManager->persist($room);
            $entityManager->persist($userGroup);
            $entityManager->flush();
            $entityManager->commit();
            return true;
        }
        catch(\Exception $e){
            //rollback
            $entityManager->rollback();
            dump($e->getMessage());
            die();
            return false;
            
        }
    }
    public function findAllByRole($role, $user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r, COUNT(ur.id) AS memberCount')  // Select room and count of members
            ->innerJoin('r.userRooms', 'ur')
            ->andWhere('ur.user = :user')
            ->andWhere('ur.role = :role')
            ->setParameter('user', $user)
            ->setParameter('role', $role)
            ->groupBy('r.id')  // Group by Room ID to count correctly
            ->getQuery()
            ->getResult();

        // dump($result);
        // die();
        return $result;
    }

    public function existsUserInRoom($user, $room): bool
{
    $count = $this->getEntityManager()->createQuery(
        'SELECT COUNT(ur) FROM App\Entity\UserRoom ur 
        WHERE ur.user = :user AND ur.room = :room'
    )
    ->setParameter('user', $user)
    ->setParameter('room', $room)
    ->getSingleScalarResult();

    return $count >= 1 ? true: false;
}

    //    public function findOneBySomeField($value): ?Room
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
