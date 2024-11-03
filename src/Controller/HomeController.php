<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Feedback;
use App\Entity\Photo;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\Catch_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\returnSelf;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $manager): Response
    {   
       // extract 10 last items from new arrivals if count > 10
        $newarrivals = $manager->getRepository(Product::class)->findBy(['Specification' => 'New Arrival']);
        $nbitems = count($newarrivals);
        $newarrivals = ($nbitems < 10 ? $newarrivals : array_slice($newarrivals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $bestsellers = $manager->getRepository(Product::class)->findBy(['Specification' => 'Best Seller']);
        $nbitems = count($bestsellers);
        $bestsellers = ($nbitems < 10 ? $bestsellers : array_slice($bestsellers, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $hotdeals = $manager->getRepository(Product::class)->findBy(['Specification' => 'Hot Deal']);
        $nbitems = count($hotdeals);
        $hotdeals  = ($nbitems < 10 ? $hotdeals : array_slice($hotdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $featured = $manager->getRepository(Product::class)->findBy(['Specification' => 'Featured']);
        $nbitems = count($featured);
        $featured = ($nbitems < 10 ? $featured : array_slice($featured,$nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $topdeals = $manager->getRepository(Product::class)->findBy(['Specification' => 'Top Deal']);
        $nbitems = count($topdeals);
        $topdeals = ($nbitems < 10 ? $topdeals : array_slice($topdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $discount = $manager->getRepository(Product::class)->findBy(['Specification' => 'Discount']);
        $nbitems = count($bestsellers);
        $discount = ($nbitems < 10 ? $discount : array_slice($discount, $nbitems-10, $nbitems-1));
        return $this->render('home/index.html.twig', [
            'newarrival' =>   $newarrivals,
            'bestseller' => $bestsellers,
            'hotdeal' => $hotdeals,
            'featured' => $featured,
            'topdeal' => $topdeals,
            'discount' => $discount,
            'prod_photos' => $manager->getRepository(Photo::class)->findAll()
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(ManagerRegistry $manager, Request $request): Response
    {
        $criteria = $request->request->get("search");
        // search the desired category
        $mycategory = $manager->getRepository(Category::class)->findOneBy(['Name' => $criteria]);
        $mybrand = $manager->getRepository(Brand::class)->findOneBy(['name' => $criteria]);
        // if found category
        if ($mycategory){
            return $this->redirectToRoute('single_category',['category'=> $criteria]);
        } else if ($mybrand){ // category not found we check for brand
            return $this->redirectToRoute('single_brand', ['brand' => $criteria]);
             }
            else {
                return $this->redirectToRoute('home');
                }
                  
    }

    #[Route('/feedback', name: 'feedback', methods:['POST'])]
    public function feedback(ManagerRegistry $manager, Request $request): Response
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

    #[Route('/contact', name: 'contact')]
    public function contact(?int $flash): Response
    {
        return $this->render('home/contact.html.twig', [
            'flash' => $flash,
        ]);
    }
}
