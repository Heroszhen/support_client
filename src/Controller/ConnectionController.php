<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LogupType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
                $user->setCreated(new \DateTime());
                $em->persist($user);
                $em->flush();

                $message = "Votre inscription a été faite avec succès";
                $this->addFlash('success', $message);

                $user = new User();
                $form = $this->createForm(LogupType::class,$user);
            }else{
                $message = "Erreurs";
                $this->addFlash('danger', $message);
            }
        }
        return $this->render('connection/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/login", name="connexionpage")
     */
    public function login(Request $request,AuthenticationUtils $authenticationUtils){
        $session = $request->getSession();
        $session->set("nav","login");
		$error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if (!empty($error)) {
            $this->addFlash('error', 'Identifiants incorrects');
        }
        return $this->render('connection/login.html.twig',[
            'last_username' => $lastUsername
        ]);
	}
    /**
     * @Route("/liste_users/{field}_{order}", name="listeusers", defaults={"field": null, "order": null})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listUsers(Request $request,$field,$order){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        if($field != null && $order != null)$allusers = $em->getRepository(User::class)->findBy([],[$field=>$order]);
        else $allusers = $em->getRepository(User::class)->findAll();
        return $this->render('connection/listusers.html.twig', [
            "allusers" => $allusers
        ]);
    }

    /**
     * @Route("/liste/user/{id}" ,name="oneuser")
     */
    public function oneUser(User $user,Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        return $this->render('connection/oneuser.html.twig', [
            "user" => $user
        ]);
    }

    /**
     * @Route("/logout")
     */
    public function logout()
    {
        //Cette méthode peut rester vide, il faut juste que sa route existe
        // pour être passée dans la section logout de config/packages/security.yaml
    }
}
