<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use App\Entity\Brand;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class User_Brand_Fixtures extends Fixture implements FixtureGroupInterface
{
    public const USER_REFERENCE = 'admin-user';

    public function load(ObjectManager $manager)
    {
        // define a password factory
        $factory = new PasswordHasherFactory(['auto' => ['algorithm' => 'auto']]);
        // use a password hasher
        $hasher = $factory->getPasswordHasher('auto');

        $admin_user = new User();
        $admin_user->setEmail("admin@myshop.com");
        $admin_user->setPassword($hasher->hash("admin"));
        $this->addReference(self::USER_REFERENCE, $admin_user);
        $manager->persist($admin_user);

        // Create brands
        $brands = ["Asus", "Dell", "HP", "Canon", "Brother", "Samsung", "Microsoft", "Google", "Epson", "Lenovo"];
        for ($i = 0; $i < count($brands); $i++) {
            $brand = new Brand();
            $brand->setName($brands[$i]);
            $brand->setDescription("brand description..." . $i);
            $manager->persist($brand);
        }

        $manager->flush();
    }

    // implnent getGroup
    public static function getGroups(): array
    {
        return ['group1'];
    }

}