<?php

namespace App\Repository;

use App\Entity\Attends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attends|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attends|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attends[]    findAll()
 * @method Attends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attends::class);
    }

    // /**
    //  * @return Attends[] Returns an array of Attends objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Attends
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
