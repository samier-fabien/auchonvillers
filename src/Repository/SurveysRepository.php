<?php

namespace App\Repository;

use App\Entity\Surveys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Surveys|null find($id, $lockMode = null, $lockVersion = null)
 * @method Surveys|null findOneBy(array $criteria, array $orderBy = null)
 * @method Surveys[]    findAll()
 * @method Surveys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Surveys::class);
    }

    /**
     * @return Surveys[] Returns an array of Votes objects
     */
    public function findLast(int $value)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.sur_created_at', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Surveys[] Returns an array of Votes objects
     */
    public function findByPage(int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.sur_created_at', 'DESC')
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
    //  * @return Surveys[] Returns an array of Surveys objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Surveys
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
