<?php

namespace App\Repository;

use App\Entity\ProductPhone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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


    public function findAllPaginated($page = 1, $limit =10): Paginator{
        $query = $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->setFirstResult($limit * ($page-1));

        return new Paginator($query);
    }
}
