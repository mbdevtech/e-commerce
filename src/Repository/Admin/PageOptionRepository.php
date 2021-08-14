<?php

namespace App\Repository\Admin;

use App\Entity\Admin\PageOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageOption[]    findAll()
 * @method PageOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageOption::class);
    }

    // /**
    //  * @return PageOption[] Returns an array of PageOption objects
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
    public function findOneBySomeField($value): ?PageOption
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
