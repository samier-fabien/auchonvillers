<?php

namespace App\Repository;

use App\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Votes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Votes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Votes[]    findAll()
 * @method Votes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Votes::class);
    }

    /**
     * @return Votes[] Returns an array of Votes objects
     */
    public function findLast(int $value)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.vot_created_at', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Votes[] Returns an array of Votes objects
     */
    public function findByPage(int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.vot_created_at', 'DESC')
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
    //  * @return Votes[] Returns an array of Votes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Votes
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
