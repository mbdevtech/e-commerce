<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    #[Route('/contact/feedback', name: 'feedback')]
    public function feedback(ManagerRegistry $manager, HttpFoundationRequest $request, MailerService $ms): Response
    {
        $customerName = $request->request->get("customerName");
        $customerEmail = $request->request->get("customerEmail");
        $contactSubject = $request->request->get("contactSubject");
        $contactMessage = $request->request->get("contactMessage");

        // validate the form inputs
        if (($customerName != null) && ($customerEmail != null) &&
            ($contactSubject != null) && ($contactMessage != null))
            {
             // save the fields data
            $feed = new Feedback();
            $feed->setName($customerName);
            $feed->setEmail($customerEmail);
            $feed->setSubject($contactSubject);
            $feed->setMessage($contactMessage);

            $manager->getManager()->persist($feed);
            try {
                $manager->getManager()->flush();
                // send confirmation mail after message registration
                $ms->twigEmailSend(3,$feed->getEmail(), $feed->getEmail(), []);
                return $this->render('contact/feedback.html.twig', ['valid'=>true]);
            } catch (\Throwable $th) 
            {
                return $this->render('contact/feedback.html.twig', ['valid'=>false]);
            } 
        }
            
        return $this->render('contact/index.html.twig');
    }

    #[Route('/contact/result', name: 'result')]
    public function result(): Response
    {
        return $this->render('contact/feedback.html.twig', [
            'flash' => 1,
        ]);
    }
}
