<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Customer;
use Faker;

class CustomerFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
 
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 3; $i++) {
            $customer = new Customer();
            $customer
                ->setName($faker->company)
                ->setSiret(str_replace(' ', '',$faker->siret))
                ->setAddress1($faker->address)
                ->setCity($faker->city)
                ->setZipcode($faker->postcode)
                ->setCreated(new \DateTime());
            
            // on doit créer l'utilisateur admin pour cette société
            $user = new User();
            $user  
                ->setLastname($faker->name)
                ->setFirstname($faker->name)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user,'aaaaaaaa'))
                ->setRoles(["ROLE_USER","ROLE_CUSTOMER","ROLE_CUSTOMER_ADMIN"])
                ->setCreated(new \DateTime());
            $manager->persist($user);

            $customer->addUser($user);
            $manager->persist($customer); 
        }
        $manager->flush();
    }
}
