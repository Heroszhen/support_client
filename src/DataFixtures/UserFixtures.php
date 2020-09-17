<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Customer;
use App\DataFixtures\ServiceFixtures;
use App\DataFixtures\CustomerFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
         // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $user = new User();
        $sc = $manager->getRepository(Service::class)->findOneByName(["support client"]);
        $user  
            ->setLastname('Héros')
            ->setFirstname('zhen')
            ->setEmail('zhen@gmail.com')
            ->setPassword($this->encoder->encodePassword($user,'aaaaaaaa'))
            ->setRoles(["ROLE_USER","ROLE_ADMIN"])
            ->setService($sc)
            ->setCreated(new \DateTime());
        $manager->persist($user);

        $faker = Faker\Factory::create('fr_FR');
         // on récupére la liste des services
        $services = $manager->getRepository(Service::class)->findAll();
         // on récupére la liste des customers
        $customers = $manager->getRepository(Customer::class)->findAll();
          
        $roles = [
            ['ROLE_USER', 'ROLE_ADMIN'], // 0
            ['ROLE_USER', 'ROLE_CUSTOMER_ADMIN', 'ROLE_CUSTOMER'], // 1
            ['ROLE_USER', 'ROLE_CUSTOMER'], // 2
        ];
        for ($i = 1; $i <= 10; $i++) {
            $type = $faker->numberBetween(0,2);
            $user = new User();
            $user
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, "aaaaaaaa"))
                ->setRoles($roles[$type])
                ->setCreated(new \DateTime());
            
            // on doit l'associer à un service
            if($type == 0) {
                $user->setService($services[array_rand($services)]);                
            } 
            // on doit l'associer à un customer
            else {
                $user->setCustomer($customers[array_rand($customers)]);  
            }  
            $manager->persist($user); 
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
