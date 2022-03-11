<?php

namespace App\Repository;

use App\Entity\TicketPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketPrice[]    findAll()
 * @method TicketPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketPrice::class);
    }

    // /**
    //  * @return TicketPrice[] Returns an array of TicketPrice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketPrice
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
