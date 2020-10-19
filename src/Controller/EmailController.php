<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class EmailController
 * @package App\Controller
 * @Route("/email")
 */
class EmailController extends AbstractController
{
    /**
     * @Route("/send", name="sendemail")
     */
    public function index(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('herosgogogogo@gmail.com')
            ->to('herosgogogogo@gmail.com')
            //->cc('[email protected]')
            //->bcc('[email protected]')
            //->replyTo('[email protected]')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
 
        $mailer->send($email);
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }
}
