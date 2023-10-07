<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\returnSelf;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): Response
    {   
        // extract 10 last items from new arrivals if count > 10
        $newarrivals = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'New Arrival']);
        $nbitems = count($newarrivals);
        $newarrivals = ($nbitems < 10 ? $newarrivals : array_slice($newarrivals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Best Seller']);
        $nbitems = count($bestsellers);
        $bestsellers = ($nbitems < 10 ? $bestsellers : array_slice($bestsellers, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $hotdeals = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Hot Deal']);
        $nbitems = count($hotdeals);
        $hotdeals  = ($nbitems < 10 ? $hotdeals : array_slice($hotdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $featured = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Featured']);
        $nbitems = count($featured);
        $featured = ($nbitems < 10 ? $featured : array_slice($featured,$nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $topdeals = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Top Deal']);
        $nbitems = count($topdeals);
        $topdeals = ($nbitems < 10 ? $topdeals : array_slice($topdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $discount = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Discount']);
        $nbitems = count($bestsellers);
        $discount = ($nbitems < 10 ? $discount : array_slice($discount, $nbitems-10, $nbitems-1));
        return $this->render('home/index.html.twig', [
            'newarrival' =>   $newarrivals,
            'bestseller' => $bestsellers,
            'hotdeal' => $hotdeals,
            'featured' => $featured,
            'topdeal' => $topdeals,
            'discount' => $discount,
            'prod_photos' => $this->getDoctrine()->getRepository(Photo::class)->findAll()
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
    public function search(Request $request): Response
    {
        $criteria = $request->request->get("search");
        // search the desired category
        $mycategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Name' => $criteria]);
        $mybrand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(['name' => $criteria]);
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

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
