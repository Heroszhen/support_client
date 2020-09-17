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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
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
            $user = null;
            $ticket = null;
            while(true){
                $user = $users[array_rand($users)];
                if(in_array("ROLE_ADMIN", $user->getRoles())){
                    if($user->getService()->getTickets()->count() > 0){
                        $ticket = $user->getService()->getTickets()[0];
                        break;
                    }
                }
            }
            $message = new Message();
            $message
                ->setAuthor($user)
                ->setTicket($ticket)
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
            UserFixtures::class
        );
    }
}
