<?php

namespace App\Repository;

use App\Controller\NewsletterController;
use App\Entity\Newsletter;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Newsletter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Newsletter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Newsletter[]    findAll()
 * @method Newsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newsletter::class);
    }

    /**
     * @return Newsletter[] Returns an array of Newsletter objects
     */
    public function findLast(int $max)
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.new_created_at', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Newsletter[] Returns an array of Newsletter objects
     */
    public function findByPage(int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.new_created_at', 'DESC')
            ->setFirstResult(($page * $numberPerPage) - $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getnumber()
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return Newsletter[] Returns an array of Newsletter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Newsletter
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
