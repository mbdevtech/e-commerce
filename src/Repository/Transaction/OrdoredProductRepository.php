<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\OrdoredProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrdoredProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdoredProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdoredProduct[]    findAll()
 * @method OrdoredProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdoredProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdoredProduct::class);
    }

    // /**
    //  * @return OrdoredProduct[] Returns an array of OrdoredProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdoredProduct
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
