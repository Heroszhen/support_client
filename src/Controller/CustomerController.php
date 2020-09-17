<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Customer;
use App\Form\CustomerWithUserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/client")
 */
class CustomerController extends AbstractController
{
    /**
     * id of customer or null
     * @Route("/creer_client/{id}", name="createcustomer",defaults={"id":null})
     */
    public function index($id,Request $request , UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        if(!$this->getUser() || !in_array("ROLE_ADMIN", $this->getUser()->getRoles()) ){
            return $this->redirectToRoute('hom');
        }

        $session = $request->getSession();
        $session->set("nav","ft");

        if($id == null){
            $user = new User();
            $customer = new Customer();
        }else{
            $customer = $em->find(Customer::class,$id);
            $user = $customer->getUsers()[0];
        }
        $form = $this->createForm(CustomerWithUserType::class, ['customer' => $customer, 'user' => $user]);
        $form->handleRequest($request);
        
        // prise en compte du formulaire
        if($form->isSubmitted() && $form->isValid()) {
            if($id == null){
                $user->setPassword($encoder->encodePassword($user,"aaaaaaaa"));
                $user->setRoles(['ROLE_USER','ROLE_CUSTOMER_ADMIN', 'ROLE_CUSTOMER']);
                $user->setCreated(new \DateTime());
            }
            
            $em->persist($user); 
            $em->flush(); 

            $customer->setCreated(new \DateTime());
            $customer->addUser($user);
 
            $em->persist($customer); 
            $em->flush(); 

            if($id == null){
                $user = new User();
                $customer = new Customer();
            }else{
                $customer = $em->find(Customer::class,$id);
                $user = $customer->getUsers()[0];
            }
            $form = $this->createForm(CustomerWithUserType::class, ['customer' => $customer, 'user' => $user]);
            $this->addFlash('success', 'Le compte client a bien été édité !');
            //return $this->redirectToRoute('hom');
        }

        return $this->render('customer/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/liste_mes_souscomptes", name="listmysubregisters")
     * @Security("is_granted('ROLE_CUSTOMER_ADMIN')")
     */
    public function listUsers(Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        $allusers = $em->getRepository(User::class)->findBy(["customer"=>$this->getUser()->getCustomer()]);

        return $this->render('customer/listmysubregisters.html.twig', [
            "allusers" => $allusers
        ]);
    }

     /**
     * @Route("/liste_clients", name="listclients")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listClients(Request $request){
        $em = $this->getDoctrine()->getManager();

        $allclients = $em->getRepository(Customer::class)->findAll();
        return $this->render('customer/listclients.html.twig', [
            "allclients" => $allclients
        ]);
    }
}
