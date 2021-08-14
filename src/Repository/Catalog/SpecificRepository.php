<?php

namespace App\Repository\Catalog;

use App\Entity\Catalog\Specific;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Specific|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specific|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specific[]    findAll()
 * @method Specific[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specific::class);
    }

    // /**
    //  * @return Specific[] Returns an array of Specific objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specific
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // public function addRow($value): ?Specific
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
    
}
