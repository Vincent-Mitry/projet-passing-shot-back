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
            ->andWhere('r.status = TRUE')
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

    /**
     * Find the upcoming reservation by users
     */

    public function upcomingReservationsByUser(User $user)
    {
        //we are looking in the reservations entity (r)
       return $this->createQueryBuilder('r')
                //join user table ('u')
                ->innerJoin('r.user', 'u')
                //comparing reservation date is > to current date
                ->where('(CAST(r.startDatetime as DATE) ) > CURRENT_TIMESTAMP()')
                //get reservations by user
                ->andWhere('u.id = :user')
                //displaying reservations by ascending dates
                ->orderBy('r.startDatetime', 'ASC')
                //value of user is defined
                ->setParameter('user', $user)
                ->getQuery()            
                ->getResult();

    }

    public function fiveLastReservationByUser(User $user)
    {
        //we are looking in the reservations entity (r)
       return $this->createQueryBuilder('r')
                    //join user table ('u')
                ->innerJoin('r.user', 'u')
                //comparing reservation date is < to current date
                ->where('(CAST(r.startDatetime as DATE) ) < CURRENT_TIMESTAMP()')
                //get reservations by user
                ->andWhere('u.id = :user')
                //displaying reservations by ascending dates
                ->orderBy('r.startDatetime', 'ASC')
                //value of user is defined
                ->setParameter('user', $user)
                //limit the number of results by 5
                ->setMaxResults(5)
                ->getQuery()            
                ->getResult();

    }

    /**
     * Gets all reservations affected by the blocked court (if a reservation's time slot is included in the blocked court's time slot)
     *
     * @param Court $court
     * @param \DateTimeInterface $blockedStartDatetime
     * @param \DateTimeInterface $blockedEndDatetime
     * @return array
     */
    public function getReservationsInBlockedCourt($court, $blockedStartDatetime, $blockedEndDatetime)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.court', 'c')
            ->where('c.id = :court')
            ->andWhere(
                '(r.startDatetime >= :blockedStartDatetime AND r.startDatetime < :blockedEndDatetime) 
                OR (r.endDatetime > :blockedStartDatetime AND r.endDatetime <= :blockedEndDatetime)'
            )
            ->setParameters([
                'court' => $court,
                'blockedStartDatetime' => $blockedStartDatetime,
                'blockedEndDatetime' => $blockedEndDatetime,
            ])
            ->getQuery()            
            ->getResult()
        ;
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
