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

    //TAO PHONG MOI
    public function createRoom($room, $user)
    {
        //viet transaction, mac dinh nguoi tao phong la admin
        try{
            $this->entityManager->beginTransaction();
            $room->setCreateDate(new \DateTime()); //set ngay tao phong
            $userGroup = new UserRoom();
            // dump("toidayroi");
            // die();
            $userGroup->setUser($user);
            $userGroup->setRoom($room);
            $userGroup->setRole("admin");
            $userGroup->setStatus("joined");
            $this->entityManager->persist($room);
            $this->entityManager->persist($userGroup);
            $this->entityManager->flush();
            $this->entityManager->commit();
            return true;
        }
        catch(\Exception $e){
            //rollback
            $this->entityManager->rollback();
            dump($e->getMessage());
            die();
            
        }
    }

    //TIM TAT CA CAC PHONG BANG ROLE
    public function findAllByRole($role, $user, $status=null)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r, COUNT(ur.id) AS memberCount')
            ->innerJoin('r.userRooms', 'ur')
            ->andWhere('ur.user = :user')
            ->andWhere('ur.role = :role')
            ->setParameter('user', $user)
            ->setParameter('role', $role)
            ->groupBy('r.id');
        if($status !== null){
            $result->andWhere('ur.status = :status')
           ->setParameter('status', $status);
        }

        // dump($result);
        // die();
        return $result->getQuery()
                    ->getResult();
    }

    //HAM KIEM TRA USER CO TRONG ROOM HAY KHONG
    public function existsUserInRoom($user, $room, $status = null): bool
    {
        $query = 'SELECT COUNT(ur) FROM App\Entity\UserRoom ur 
                WHERE ur.user = :user AND ur.room = :room';
        
        if ($status !== null) {
            $query .= ' AND ur.status = :status';
        }
    
        $qb = $this->getEntityManager()->createQuery($query)
            ->setParameter('user', $user)
            ->setParameter('room', $room);
    
        if ($status !== null) {
            $qb->setParameter('status', $status);
        }
    
        $count = $qb->getSingleScalarResult();
    
        return $count >= 1;
    }
    

    //HAM KIEM TRA NGUOI DUNG TRONG ROOM CO VAI TRO DO HAY KHONG
    public function isRole($user, $room, $role){
        $query = $this->createQueryBuilder('r')
                        ->innerJoin('r.userRooms', 'ur')
                        ->where('r.id = :room')
                        ->andWhere('ur.user = :user')
                        ->andWhere('ur.role = :role')
                        ->setParameter('user', $user)
                        ->setParameter('role', $role)
                        ->setParameter('room', $room);
        
        $result = $query->getQuery()->getScalarResult();
        return $result >= 1;

    }


    //TIM TAT CA CAC THANH VIEN DUA VAO ROOM
    public function findUserByRoom($room, $role = 'member', $status = null)
    {
        $dql = 'SELECT ur FROM App\Entity\UserRoom ur WHERE ur.role = :role AND ur.room = :room';
        
        if ($status !== null) {
            $dql .= ' AND ur.status = :status';
        }
    
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('room', $room)
            ->setParameter('role', $role);
    
        if ($status !== null) {
            $query->setParameter('status', $status);
        }

        // dump( $query->getResult());
        // die();

        return $query->getResult();
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
