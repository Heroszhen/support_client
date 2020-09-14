<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('hom/index.html.twig', [
            
        ]);
    }
}
