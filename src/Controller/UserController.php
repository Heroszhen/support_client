<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\CreateType;
use App\Form\ModifuserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/creation",name="createuser")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        if(!$this->getUser() || !in_array("ROLE_ADMIN", $this->getUser()->getRoles()) ){
            return $this->redirectToRoute('hom');
        }

        $session = $request->getSession();
        $session->set("nav","ft");
        $user = new User();
        $form = $this->createForm(CreateType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $user->setCreated(new \DateTime());
                $em->persist($user);
                $em->flush();

                $message = "Un utilisateur a été créé avec succès";
                $this->addFlash('success', $message);

                $user = new User();
                $form = $this->createForm(CreateType::class,$user);
            }else{
                $message = "Erreurs";
                $this->addFlash('danger', $message);
            }
        }

        return $this->render('user/index.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/creer_souscompte",name="createsouscompte")
     */
    public function subRegister(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        //if(!$this->getUser() || !$this->getUser()->getCustomer() || !in_array("ROLE_CUSTOMER_ADMIN", $this->getUser()->getRoles()) )return $this->redirectToRoute('hom');
        

        $user = new User();
        $form = $this->createForm(CreateType::class, $user, ['type_register' => 'sub_account']);
        $form->handleRequest($request);
        
        // prise en compte du formulaire
        if($form->isSubmitted() && $form->isValid()) {
 
            $user->setCustomer($this->getUser()->getCustomer());
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setCreated(new \DateTime());
   
            $em->persist($user); 
            $em->flush(); 
            //$this->addFlash('success', 'Le compte utilisateur a bien été créé !');
            return $this->redirectToRoute('hom');
        }
 
        return $this->render('user/souscompte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier_user/{id}",name="modifuser")
     */
    public function modifierOneUser(User $user,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //if(!$this->getUser() || !$this->getUser()->getCustomer() || !in_array("ROLE_CUSTOMER_ADMIN", $this->getUser()->getRoles()) )return $this->redirectToRoute('hom');

        if($user->getCustomer() != null)$form = $this->createForm(ModifuserType::class, $user,['type_register' => 'sub_account']);
        else $form = $this->createForm(ModifuserType::class, $user);
        $form->handleRequest($request);
        
        // prise en compte du formulaire
        if($form->isSubmitted() && $form->isValid()) {
   
            $em->persist($user); 
            $em->flush(); 
            $this->addFlash('success', 'Le compte utilisateur a bien été édité!');
            //return $this->redirectToRoute('hom');
        }
 
        return $this->render('user/souscompte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
