<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('account/index.html.twig');
    }

    #[Route('/login', name: 'user_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser())
        {
             
            return $this->redirectToRoute("home");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/register', name: 'user_register')]
    public function register(ManagerRegistry $em, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->request->get('password') !== $request->request->get('confirm')) {
            echo ('Confirmation Password does not match with Password...');
        } else {
            if ($request->request->get('email') != null && 
                $request->request->get('password') != null) {
                $user = new User();
                # $user->firstname = $request->request->get('first_name');
                # $user->lastname = $request->request->get('last_name');
                $user->setEmail($request->request->get('email'));
                $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
                $em->getManager()->persist($user);
                $em->getManager()->flush();
                return $this->redirect("/");
            }
        }
        return $this->render('account/register.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
