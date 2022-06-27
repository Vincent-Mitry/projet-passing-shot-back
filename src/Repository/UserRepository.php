<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * Find last three users for home
    */
    public function findLastThree()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_MEMBER%')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults('3')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * Find users by lastname
    */
    public function getUserListByLastname($search)
    {
        return $this->createQueryBuilder('u')
            ->where('u.lastname LIKE :search')
            ->setParameter('search', $search.'%')
            ->orderBy('u.firstname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * Get all users member
    */
    public function getUsersMemberList()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_MEMBER%')
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * Get all users staff
    */
    public function getUsersBackOffice()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :admin')
            ->orWhere('u.roles LIKE :superadmin')
            ->setParameter('admin', '%ROLE_ADMIN%')
            ->setParameter('superadmin', '%ROLE_SUPER_ADMIN%')
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
