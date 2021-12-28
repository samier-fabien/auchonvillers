<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    /**
     * @return Events[] Returns an array of Events objects
     */
    public function findLast(int $value)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.eve_created_at', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Events[] Returns an array of Events objects
     */
    public function findByPage(int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.eve_created_at', 'DESC')
            ->setFirstResult(($page * $numberPerPage) - $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getnumber()
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return Events[] Returns an array of Events objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Events
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
