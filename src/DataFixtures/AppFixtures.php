<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Products;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Products();
        $product->setName("Produit 1");
        $product->setDescription("description 1");
        $product->setSize("100");

        $manager->persist($product);

        $product = new Products();
        $product->setName("Produit 2");
        $product->setDescription("description 2");
        $product->setSize("200");

        $manager->persist($product);

        $manager->flush();
    }
}
