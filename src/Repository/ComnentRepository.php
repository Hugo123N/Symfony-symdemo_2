<?php

namespace App\Repository;

use App\Entity\Comnent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comnent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comnent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comnent[]    findAll()
 * @method Comnent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComnentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comnent::class);
    }

    // /**
    //  * @return Comnent[] Returns an array of Comnent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comnent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
