<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Entity\Profile;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
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
    public function register(ManagerRegistry $em, Request $request, UserPasswordHasherInterface $hasher, MailerService $ms): Response
    {
    
        if ($request->request->get('email') != null && 
                $request->request->get('password') != null &&
                $request->request->get('password') === $request->request->get('confirm'))
         {
                $user = new User();
                $user->setEmail($request->request->get('email'));
                $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
                $em->getManager()->persist($user);
                $em->getManager()->flush();
                               
                try {

                // create the profile
                    $profile = new Profile();
                    $profile->setUser($user);
                    $profile->setFirstName($request->request->get('first_name'));
                    $profile->setLastName($request->request->get('last_name'));
                    $profile->setEmail($request->request->get('email'));
                    $profile->setPhoneNumber('XXXXXXXXXX');
                    $profile->setPhoto('image.png');
                    $em->getManager()->persist($profile);
                    $em->getManager()->flush();                    

                    // send confirmation mail after user registration with items list
                    $ms->twigEmailSend(1,$user->getEmail(), $user->getEmail(), []);
                    return $this->render('account/validation.html.twig', ['valid'=>true]);
                } catch (\Throwable $th) 
                {
                    return $this->render('account/validation.html.twig', ['valid'=>false]);
                }        
        }
        return $this->render('account/register.html.twig');
    }

    #[Route('/profile', name: 'user_profile')]
    public function profile(ManagerRegistry $em, Request $request, UserPasswordHasherInterface $hasher, MailerService $ms): Response
    {
        $user = $this->getUser();                            
        $profile = $em->getManager()->getRepository(Profile::class)->findOneBy(['User' => $user]);

        $firstname = $request->request->get('first_name') ;
        $lastname = $request->request->get('last_name');
        $email = $request->request->get('email');
        $photo = $request->request->get('photo');
        $phone = $request->request->get('phone');
        
        if($lastname && $firstname && $email && $phone && $photo)
        {
            try {
                dd($profile);
    
                    $profile->setFirstName($firstname) ;
                    $profile->setLastName($lastname);
                    $profile->setEmail($email);
                    $profile->setPhoneNumber($phone);
                    $profile->setPhoto($photo);
                    
                    //$user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
                    $em->getManager()->persist($profile);
                    
                    $em->getManager()->flush();
                    // send confirmation mail after profile update
                    //$ms->twigEmailSend(1,$user->getEmail(), $user->getEmail(), []);
                    return $this->render('account/validation.html.twig', ['valid'=>true]);
            } catch (\Throwable $th) 
                    {
                        return $this->render('account/validation.html.twig', ['valid'=>false]);
                    }
        }
                
        
        return $this->render('account/profile.html.twig', ['profile' => $profile]);
    }

    #[Route('/history', name: 'user_history')]
    public function history(ManagerRegistry $em, Request $request, UserPasswordHasherInterface $hasher, MailerService $ms): Response
    {
    
        if ($request->request->get('email') != null && 
                $request->request->get('password') != null &&
                $request->request->get('password') === $request->request->get('confirm'))
         {
                $user = new User();
                # $user->firstname = $request->request->get('first_name');
                # $user->lastname = $request->request->get('last_name');
                $user->setEmail($request->request->get('email'));
                $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
                $em->getManager()->persist($user);
                try {
                    $em->getManager()->flush();
                    // send confirmation mail after user registration with items list
                    $ms->twigEmailSend(1,$user->getEmail(), $user->getEmail(), []);
                    return $this->render('account/validation.html.twig', ['valid'=>true]);
                } catch (\Throwable $th) 
                {
                    return $this->render('account/validation.html.twig', ['valid'=>false]);
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
