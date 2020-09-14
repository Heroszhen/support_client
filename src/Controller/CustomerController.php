<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Customer;
use App\Form\CustomerWithUserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/client")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/creer_client", name="createcustomer")
     */
    public function index(Request $request , UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $customer = new Customer();
        $form = $this->createForm(CustomerWithUserType::class, ['customer' => $customer, 'user' => $user]);
 
        $form->handleRequest($request);
        
        // prise en compte du formulaire
        if($form->isSubmitted() && $form->isValid()) {
 
            $user->setPassword($encoder->encodePassword($user, \uniqid('password_bidon')));
            $user->setRoles(['ROLE_USER','ROLE_CUSTOMER_ADMIN', 'ROLE_CUSTOMER']);
            $user->setCreated(new \DateTime());
            $em->persist($user); 
            $em->flush(); 

            $customer->setCreated(new \DateTime());
            $customer->addUser($user);
 
            $em->persist($customer); 
            $em->flush(); 
            //$this->addFlash('success', 'Le compte client a bien été créé !');
            return $this->redirectToRoute('hom');
        }

        return $this->render('customer/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
