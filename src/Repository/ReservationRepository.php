<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function add(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllReservationsByDateAndCourt($date, $court)
    {
        return $this->createQueryBuilder('r')
            ->where('CAST(r.startDatetime as DATE) = :date')
            ->andWhere('r.court = :court')
            ->setParameter('date', $date)
            ->setParameter('court', $court)
            ->getQuery()            
            ->getResult()
        ;
    }
  
    /**
    * Find last three reservations for home
    */
    public function findLastThree()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r
            FROM App\Entity\Reservation r
            ORDER BY r.id DESC'
        )->setMaxResults(3);

        return $query->getResult();
    }

    public function upcomingReservationsByUser1(User $user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u.lastname, u.firstname, r.startDatetime, u.id
            FROM App\Entity\User u 
            INNER JOIN App\Entity\Reservation r 
            INNER JOIN --u.id = r.user_id
            WHERE (CAST(r.startDatetime as DATE) ) > CURRENT_TIMESTAMP()
            AND u.id = :user
            ORDER BY r.startDatetime ASC'
            ) -> setParameter('user', $user);
            
            return $query->getResult();
    }

    public function upcomingReservationsByUser(User $user)
    {
       return $this->createQueryBuilder('r')
                ->innerJoin('r.user', 'u')
                ->where('(CAST(r.startDatetime as DATE) ) > CURRENT_TIMESTAMP()')
                ->andWhere('u.id = :user')
                ->orderBy('r.startDatetime', 'ASC')
                ->setParameter('user', $user)
                ->getQuery()            
                ->getResult();

    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
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

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
