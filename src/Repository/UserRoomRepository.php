<?php

namespace App\Repository;

use App\Entity\UserRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<UserRoom>
 *
 * @method UserRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRoom[]    findAll()
 * @method UserRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoomRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, UserRoom::class);
    }

    /**
     * Thêm một UserRoom vào database
     */
    public function save(UserRoom $userRoom, bool $flush = true): void
    {
        $this->_em->persist($userRoom);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Xóa một UserRoom khỏi database
     */
    public function remove(UserRoom $userRoom, bool $flush = true): void
    {
        $this->_em->remove($userRoom);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
