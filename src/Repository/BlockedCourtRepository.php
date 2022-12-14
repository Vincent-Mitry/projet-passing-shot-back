<?php

namespace App\Repository;

use App\Entity\BlockedCourt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlockedCourt>
 *
 * @method BlockedCourt|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockedCourt|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockedCourt[]    findAll()
 * @method BlockedCourt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockedCourtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockedCourt::class);
    }

    public function add(BlockedCourt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlockedCourt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getBlockedCourtsByDateAndCourt($date, $court)
    {
        return $this->createQueryBuilder('b')
            ->where(':date BETWEEN CAST(b.startDatetime as DATE) AND b.endDatetime')
            ->andWhere('b.court = :court')
            ->setParameter('date', $date)
            ->setParameter('court', $court)
            ->getQuery()            
            ->getResult()
        ;
    }

    public function findBlockedCourtsByCourt($court)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.court', 'c')
            ->where('c.id = :court')
            ->orderBy('b.startDatetime', 'DESC')
            ->setParameter('court', $court)
            ->getQuery()            
            ->getResult()
        ;
    }

    public function getCurrentBlockedCourts($now)
    {
        return $this->createQueryBuilder('b')
            ->where('b.startDatetime <= :now AND :now < b.endDatetime')
            ->setParameter('now', $now)
            ->getQuery()            
            ->getResult()
        ;
    }

    public function getPastBlockedCourts($now)
    {
        return $this->createQueryBuilder('b')
            ->where('b.endDatetime < :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFutureBlockedCourts($now)
    {
        return $this->createQueryBuilder('b')
            ->where(':now < b.startDatetime')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return BlockedCourt[] Returns an array of BlockedCourt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BlockedCourt
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
