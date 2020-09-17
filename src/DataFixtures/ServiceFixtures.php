<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Service;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
 
        $services = [
             'support client', 'Comptabilité', 
             'Commercial', 'Développement', 
             'Administration réseau', 'Graphisme', 'Référencement'
       ];
        foreach($services as $service) {
            $oService = new Service();
            $oService
                    ->setName($service);
 
            $manager->persist($oService);
        }
        $manager->flush();

    }
}
