<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\CreateType;
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

}
