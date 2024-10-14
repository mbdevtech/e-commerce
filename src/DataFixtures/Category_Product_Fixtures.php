<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Photo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class Category_Product_Fixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            "Computers", "Laptops", "Servers", "Workstations", "All-in-ones", "Tablets", "Samsung", "Nexus", "Apple",
            "Microsoft", "Lenovo", "Printers", "Canon", "Lexmark", "Brother", "Xerox", "HPLaserJet", "Accessories",
            "RAM", "Stockage", "Cards", "CAMs", "CPUs", "DVRs", "Screens", "Network", "Security"
        ];

        $brands = $manager->getRepository(Brand::class)->findAll();

        $specifications = ["New Arrival","Best Seller", "Hot Deal", "Featured", "Discount", "Top Deal"];
        
        // for each category we create a few products
        for ($j = 0; $j < count($categories); $j++) {
            $cat = new Category();
            $cat->setName($categories[$j]);
            $cat->setExcerpt($categories[$j] . ' description... ');
            $cat->setParentId(0); // all main categories
            $cat->setIcon("");
            $manager->persist($cat);
            // create products
            for ($i = 0; $i < mt_rand(100, 300); $i++) {
                $product = new Product();
                $product->setName('product ' . $i . ' of ' . $cat->getName());
                $product->setExcerpt('product ' . $i . ' of ' . $cat->getName());
                $product->setUser($this->getReference(User_Brand_Fixtures::USER_REFERENCE));
                $product->setCategory($cat);
                // set the other fields
                $product->setPrice(mt_rand(9.99, 99.99));
                $product->setQuantity(mt_rand(3, 25));
                $product->setDescription("Lorem ipsum vetgt ulu vetsic Lorem ipsum vetgt ulu vetsic");
                $product->setEditedAt(new \DateTime());
                $product->setBrand($brands[mt_rand(0, count($brands)-1)]);
                // give a random thumb photo
                $path = 'layout/images/product/large-size/' . mt_rand(1, 13) . '.jpg';
                $product->setThumb($path);
                // give a random specification
                $product->setSpecification($specifications[mt_rand(0, count($specifications) - 1)]);
                // sets a random brand
                $product->setBrand($brands[mt_rand(0, count($brands) - 1)]);
                // add 5 photos to each product
                $this->addPhotos($manager, $product);
                $manager->persist($product);
                // add the product to the brand

            }
        }
        $manager->flush();
    }
    // for each product add 5 photo
    public function addPhotos(ObjectManager $om, Product $p)
    {
        for ($i = 0; $i < 5; $i++) {
            $photo = new Photo();
            $photo->setProduct($p);
            $photo->setTitle($p->getName());
            $photo->setCaption($p->getName());
            $path = 'layout/images/product/large-size/' . mt_rand(1, 13) . '.jpg';
            $photo->setUrl($path);
            $p->addPhoto($photo);
            $om->persist($photo);
        }
    }

    // implnent getGroup
    public static function getGroups(): array
    {
         return ['group2'];
    }

    // implements dependencies
    public function getDependencies()
    {
        return array(
            User_Brand_Fixtures::class,
        );
    }

}
