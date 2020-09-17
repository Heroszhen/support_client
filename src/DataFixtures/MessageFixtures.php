<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Ticket;
use App\Entity\Message;
use App\DataFixtures\TicketFixtures;
use App\DataFixtures\UserFixtures;

class MessageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $users = $manager->getRepository(User::class)->findAll();
        $tickets = $manager->getRepository(Ticket::class)->findAll();

        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 10; $i++) {
            $message = new Message();
            $message
                ->setAuthor($users[array_rand($users)])
                ->setTicket($tickets[array_rand($tickets)])
                ->setContent($faker->text($maxNbChars = 30))
                ->setCreated(new \DateTime());
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TicketFixtures::class,
            UserFixtures::class,
        );
    }
}
