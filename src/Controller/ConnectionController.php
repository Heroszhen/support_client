<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LogupType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ConnectionController extends AbstractController
{
    /**
     * @Route("/inscription", name="registerpage")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","logup");

        $user = new User();
        $form = $this->createForm(LogupType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());dump($user->getPassword());
                $user->setPassword($password);

                $user->setCreated(new \DateTime());
                $em->persist($user);
                $em->flush();

                $message = "Votre inscription a été faite avec succès";
                $this->addFlash('success', $message);
            }else{
                $message = "Erreurs";
                $this->addFlash('danger', $message);
            }
        }
        return $this->render('connection/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
