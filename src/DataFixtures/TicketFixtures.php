<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Ticket;
use App\Entity\Service;
use App\Entity\Customer;
use App\DataFixtures\ServiceFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CustomerFixtures;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $faker = Faker\Factory::create('fr_FR');
         // on récupére la liste des services
        $services = $manager->getRepository(Service::class)->findAll();
         // on récupére la liste des customers
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $user = null;
            while(true){
                $user = $users[array_rand($users)];
                if(in_array("ROLE_CUSTOMER_ADMIN", $user->getRoles()))break;
            }
            $sc = $manager->getRepository(Service::class)->findOneByName(["support client"]);
            $ticket = new Ticket();
            $ticket
                ->setAuthor($user)
                ->setCustomer($user->getCustomer())
                ->setTitle($faker->text($maxNbChars = 10))
                ->setDescription($faker->text($maxNbChars = 100))
                ->setPriority($faker->numberBetween(0,3))
                ->setStatus(0)
                ->addService($sc)
                ->setCreated(new \DateTime());
            $manager->persist($ticket);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ServiceFixtures::class,
            CustomerFixtures::class,
        );
    }
}
