<?php

namespace App\Repository;

use App\Entity\XRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method XRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method XRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method XRate[]    findAll()
 * @method XRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XRate::class);
    }

    // /**
    //  * @return XRate[] Returns an array of XRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('x.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?XRate
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
