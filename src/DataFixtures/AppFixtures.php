<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Photo;
use App\Entity\Specification;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $categories = [
            "Computers", "Laptops", "Servers", "Workstations", "All-in-ones", "Tablets", "Samsung", "Nexus", "Apple",
            "Microsoft", "Lenovo", "Printers", "Canon", "Lexmark", "Brother", "Xerox", "HPLaserJet", "Accessories",
            "RAM", "Stockage", "Cards", "CAMs", "CPUs", "DVRs", "Screens", "Network", "Security"
        ];
        // extract the admin user with id 4
        $admin_user = new User();
        $admin_user->setEmail("admin@myshop.com");
        $admin_user->setPassword("admin");
        $manager->persist($admin_user);
        // for each category we create a few products
        for ($j = 0; $j < count($categories); $j++) {
            $cat = new Category();
            $cat->setName($categories[$j]);
            $cat->setExcerpt($categories[$j] . ' description... ');
            $cat->setParentId(0); // all main categories
            $manager->persist($cat);
            // create products
            for ($i = 0; $i < mt_rand(1, 6); $i++) {
                $product = new Product();
                $product->setName('product ' . $i . ' of ' . $cat->getName());
                $product->setExcerpt('product ' . $i . ' of ' . $cat->getName());
                $product->setUser($admin_user);
                $product->setCategory($cat);
                $product->setPrice(mt_rand(9.99, 99.99));
                $product->setQuantity(mt_rand(3, 15));
                $product->setDescription("Lorem ipsum vetgt ulu vetsic Lorem ipsum vetgt ulu vetsic");
                $product->setEditedAt(new \DateTime());
                $this->addPhotos($manager, $product);
                $this->addSpecifications($manager, $product);
                $manager->persist($product);
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
    // for each product add A specification
    public function addSpecifications(ObjectManager $om, Product $p)
    {
        $specifications = ["New Arrival", "Best Seller", "Hot Deal", "Featured", "Top Deal", "Discount"];
        $specific = new Specification();
        $specific->setProduct($p);
        $specific->setName($specifications[mt_rand(0, 5)]);
        $specific->setValue(1);
        $om->persist($specific);
    }
}
