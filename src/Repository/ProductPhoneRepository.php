<?php

namespace App\Repository;

use App\Entity\ProductPhone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductPhone|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPhone|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPhone[]    findAll()
 * @method ProductPhone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPhone::class);
    }

    // /**
    //  * @return Phone[] Returns an array of Phone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Phone
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
