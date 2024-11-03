<?php

namespace App\Controller;

use App\Entity\Feedback;
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
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/contact/feedback', name: 'feedback', methods:['POST'])]
    public function feedback(ManagerRegistry $manager, HttpFoundationRequest $request): Response
    {

        $customerName = $request->request->get("customerName");
        $customerEmail = $request->request->get("customerEmail");
        $contactSubject = $request->request->get("contactSubject");
        $contactMessage = $request->request->get("contactMessage");

        // validate the form inputs
        if (($customerName != null) && ($customerEmail != null) &&
            ($contactSubject != null) && ($contactMessage != null)){
             // save the fields data
            $feed = new Feedback();
            $feed->setName($customerName);
            $feed->setEmail($customerEmail);
            $feed->setSubject($contactSubject);
            $feed->setMessage($contactMessage);

            $manager->getManager()->persist($feed);
            $manager->getManager()->flush();
            
            $flash = 1;
            }
            else{
                $flash = 2;
             }  
            
            return $this->redirectToRoute('contact', ['flash' => $flash]);
    }
}
