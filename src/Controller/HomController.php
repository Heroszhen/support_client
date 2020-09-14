<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeController
 * @package App\Controller
 *
 */
class HomController extends AbstractController
{
    /**
     * @Route("/", name="hom")
     */
    public function index(Request $request)
    {
        //$em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $session->set("nav","home");
        return $this->render('hom/index.html.twig', [
            
        ]);
    }
}
