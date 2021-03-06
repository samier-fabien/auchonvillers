<?php

namespace App\Repository;

use App\Entity\Ballots;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ballots|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ballots|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ballots[]    findAll()
 * @method Ballots[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BallotsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ballots::class);
    }

    /**
     * @return Ballots[] Returns an array of Votes objects
     */
    public function findPerVoteAndUser(int $vote, int $user)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.vote = :vote')
            ->andWhere('b.user = :user')
            ->setParameter('vote', $vote)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ballots[] Returns an array of Votes objects
     */
    public function findResults(int $vote)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.vote = :vote')
            ->setParameter('vote', $vote)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Ballots[] Returns an array of Ballots objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ballots
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
