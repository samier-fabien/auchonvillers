<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User Returns an array of User objects
     */
    public function findOneByRole(string $role): ?User
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%'.$role.'%')
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByRole(string $role): ?User
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%'.$role.'%')
        ->getQuery()
        ->getResult()
        ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByRoleAndPage(string $role, int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.roles LIKE :role')
        ->orderBy('u.created_at', 'DESC')
        ->setFirstResult(($page * $numberPerPage) - $numberPerPage)
        ->setMaxResults($numberPerPage)
        ->setParameter('role', '%'.$role.'%')
        ->getQuery()
        ->getResult()
        ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByPage(int $page = 1, int $numberPerPage)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.created_at', 'DESC')
            ->setFirstResult(($page * $numberPerPage) - $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getQuery()
            ->getResult()
        ;
    }

    // public function getnumber()
    // {
    //     return $this->createQueryBuilder('a')
    //         ->select('count(a.id)')
    //         ->getQuery()
    //         ->getSingleScalarResult()
    //     ;
    // }

    public function getnumber(string $role)
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
