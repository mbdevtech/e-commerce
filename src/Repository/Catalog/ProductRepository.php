<?php

namespace App\Repository\Catalog;

use App\Entity\Catalog\Product;
use App\Entity\Catalog\Photo;
use App\Entity\Catalog\Category;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findWithPhotos()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return Product[]
     */

    public function findWithPhotos(): array
    {
        $qb = $this->createQueryBuilder('prod')
            ->select('p.id', 'p.name', 'p.price', 'p.excerpt', 'f.path')
            ->from('App\Entity\Catalog\Product', 'p')
            ->innerJoin('App\Entity\Catalog\Photo', 'f', 'p.id = f.product_id')
            ->getQuery();

        return $qb->execute();
    }
    //  One product with photo (join)
    public function findOneProductPhoto(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT p.id, p.profile_id, p.category_id, p.name, p.excerpt, p.price, p.quantity, p.brand, f.path 
                FROM product p JOIN photo f WHERE p.id = f.product_id
                and  (p.id = :id)
                ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
    //  products with photo (join)
    public function findAllProductPhoto(?int $category): array
    {
        $conn = $this->getEntityManager()->getConnection();
        if ($category != 0) {
            $sql = '
                SELECT p.id, p.category_id, p.name, p.excerpt, p.price, f.path 
                FROM product p JOIN photo f WHERE p.id = f.product_id
                and (p.category_id = :category) ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['category' => $category]);
        } else {
            $sql = '
                SELECT p.id, p.category_id, p.name, p.excerpt, p.price, f.path 
                FROM product p JOIN photo f WHERE p.id = f.product_id';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAllAssociative();
    }

    //  Other alternate products with photo (join)
    public function findOtherProductPhoto(int $category, int $product): array
    {
        $conn = $this->getEntityManager()->getConnection();
        if ($category != 0) {
            $sql = '
                SELECT p.id, p.category_id, p.name, p.excerpt, p.price, f.path 
                FROM product p JOIN photo f WHERE p.id = f.product_id
                and ((p.category_id = :category) and (p.id != :product)) LIMIT 14 ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['category' => $category, 'product' => $product]);
        } else {
            $sql = '
            SELECT p.id, p.category_id, p.name, p.excerpt, p.price, f.path 
            FROM product p JOIN photo f WHERE p.id = f.product_id
            and (p.id != :product) LIMIT 14 ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['product' => $product]);
        }
        return $stmt->fetchAllAssociative();
    }

    //  discount with photo
    public function findAllDiscounts(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT p.id, p.name, p.excerpt, p.price, d.start_date, d.expiry_date, d.rate, f.path 
            FROM product p, photo f, discount d  WHERE  p.id = f.product_id AND p.id = d.product_id
            ';
        $stmt = $conn->prepare($sql);
        //$stmt->execute(['min' => $min, 'max' => $max]);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
    // product by category
    //  discount with photo
    public function findByCategory(int $category_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * from product p  WHERE  (p.category_id = @category_id)';
        //$sql = "SELECT * from product  where category_id = 108";
        $stmt = $conn->prepare($sql);
        //$stmt->execute(['min' => $min, 'max' => $max]);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
}
