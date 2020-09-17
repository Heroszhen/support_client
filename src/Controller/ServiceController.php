<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Ticket;
use App\Form\ServiceType;
use App\Form\TicketType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Message;
use App\Form\MessageType;

/**
 * Class ServiceController
 * @package App\Controller
 * @Route("/Service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/creer_service/{id}", name="createservice",defaults={"id":null})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if(!$this->getUser() || !in_array("ROLE_ADMIN", $this->getUser()->getRoles()) )return $this->redirectToRoute('hom');

        $session = $request->getSession();
        $session->set("nav","ft");

        if($id == null)$service = new Service();
        else $service = $em->find(Service::class,$id);
        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                
                $em->persist($service);
                $em->flush();

                $message = "Un service a été édité avec succès";
                $this->addFlash('success', $message);

                if($id == null)$service = new Service();
                else $service = $em->find(Service::class,$id);
                $form = $this->createForm(ServiceType::class,$service);

            }else{
                $message = "Erreurs";
                $this->addFlash('danger', $message);
            }
        }
        return $this->render('service/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/tous-les-services", name="allservices")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function getAllServices(Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        $list = $em->getRepository(Service::class)->findAll();
        return $this->render('service/listservices.html.twig', [
            "list" => $list
        ]);
    }


    /**
     * @Route("/creer_ticket/{id}", name="createticket",defaults={"id":null})
     * @Security("is_granted('ROLE_CUSTOMER') or is_granted('ROLE_CUSTOMER_ADMIN') or is_granted('ROLE_ADMIN')")
     */
    public function createOneTicket($id,Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        if($id == null)$ticket = new Ticket();
        else $ticket = $em->find(Ticket::class,$id);
        $oldfile = $ticket->getFile();
        $form = $this->createForm(TicketType::class,$ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                if($id == null){
                    $support = $em->find(Service::class,1);
                    $ticket->addService($support);
                    $ticket->setAuthor($this->getUser());
                    $ticket->setCustomer($this->getUser()->getCustomer());
                    $ticket->setCreated(new \DateTime());
                }
                $file = $ticket->getFile();
                if (!is_null($file)) {
                    $newimg = uniqid() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('upload_dir'), $newimg);
                    $ticket->setFile($newimg);
                    if ($oldfile != null) unlink($this->getParameter('upload_dir') .$oldfile);
                } else {
                    $ticket->setFile($oldfile);
                }
                $em->persist($ticket);
                $em->flush();

                $message = "Un ticket a été édité avec succès";
                $this->addFlash('success', $message);

                if($id == null)$ticket = new Ticket();
                else $ticket = $em->find(Ticket::class,$id);
                $form = $this->createForm(TicketType::class,$ticket);

            }else{
                $message = "Erreurs";
                $this->addFlash('danger', $message);
            }
        }
        return $this->render('service/createticket.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/tous-mes-tickets/{field}_{order}", name="alltickets",defaults={"field":null,"order":null})
     * @Security("is_granted('ROLE_CUSTOMER') or is_granted('ROLE_CUSTOMER_ADMIN')")
     */
    public function allTickets($field,$order,Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        if($field != null && $order != null)$alltickets = $em->getRepository(Ticket::class)->findBy(["customer"=>$this->getUser()->getCustomer()],[$field=>$order]);
        else $alltickets = $em->getRepository(Ticket::class)->findBy(["customer"=>$this->getUser()->getCustomer()]);

        $allservices = $em->getRepository(Service::class)->findBy([],["name"=>"asc"]);
        return $this->render('service/listtickets.html.twig', [
            "list" => $alltickets,
            "allservices" => $allservices
        ]);
    }

    /**
     * @Route("/tous-les-tickets/", name="adminalltickets")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function adminAllTickets(Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->set("nav","ft");

        $alltickets = $em->getRepository(Ticket::class)->findAll();
        $allservices = $em->getRepository(Service::class)->findBy([],["name"=>"asc"]);
        /*
        $ts = $em->getRepository(Ticket::class)->findByService($this->getUser()->getService());
        $ts2 = $em->getRepository(Ticket::class)->findByNotService($this->getUser()->getService());
        dump($ts,$ts2);*/
        return $this->render('service/adminlisttickets.html.twig', [
            "list" => $alltickets,
            "allservices" => $allservices
        ]);
    }



    /**
     * @Route("/switch_ticket_service/{serviceid}_{ticketid}",name="switchticketservice")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CUSTOMER_ADMIN')")
     */
    public function switchTicketService($serviceid, $ticketid ,Request $request){
        $em = $this->getDoctrine()->getManager();

        $service = $em->find(Service::class,$serviceid);
        $ticket = $em->find(Ticket::class,$ticketid);
        if($ticket->getServices()->contains($service))$ticket->removeService($service);
        else $ticket->addService($service);

        $em->persist($ticket);
        $em->flush();

        return $this->redirectToRoute('adminalltickets');
    }

    /**
     * @Route("/ticket/{id}", name="messagetoticket")
     * 
     */
    public function sendMessageToTicket(Ticket $ticket, Request $request){
        $em = $this->getDoctrine()->getManager();


        $message = new Message();
        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $message->setAuthor($this->getUser());
                $message->setTicket($ticket);
                $message->setCreated(new \DateTime());
                $em->persist($message);
                $em->flush();

                $message = new Message();
                $form = $this->createForm(MessageType::class,$message);
            }
        }
        
        return $this->render('service/messageticket.html.twig', [
            "ticket" => $ticket,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/moteur_recherche", name="moteurrecherche")
     * 
     */
    public function moteurrecherche(Request $request){
        $em = $this->getDoctrine()->getManager();

        $recherche = $request->request->get("recherche");
        $results = [];
        if($recherche != null){
            $results = $em->getRepository(Ticket::class)->moteur($recherche);
        }
        return $this->render('service/moteurrecherche.html.twig', [
            "recherche" => $recherche,
            "list" => $results
        ]);
    }

}
