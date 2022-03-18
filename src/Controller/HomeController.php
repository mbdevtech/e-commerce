<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Specification;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\returnSelf;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // extract 10 last items from new arrivals if count > 10
        $newarrivals = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'New Arrival', 'Value' => 1]);
        $nbitems = count($newarrivals);
        $newarrivals = ($nbitems < 10 ? $newarrivals : array_slice($newarrivals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $bestsellers = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Best Seller', 'Value' => 1]);
        $nbitems = count($bestsellers);
        $bestsellers = ($nbitems < 10 ? $bestsellers : array_slice($bestsellers, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $hotdeals = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Hot Deal', 'Value' => 1]);
        $nbitems = count($hotdeals);
        $hotdeals  = ($nbitems < 10 ? $hotdeals : array_slice($hotdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $featured = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Featured', 'Value' => 1]);
        $nbitems = count($featured);
        $featured = ($nbitems < 10 ? $featured : array_slice($featured,$nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $topdeals = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Top Deal', 'Value' => 1]);
        $nbitems = count($topdeals);
        $topdeals = ($nbitems < 10 ? $topdeals : array_slice($topdeals, $nbitems-10, $nbitems-1));
        // extract 10 last items from new arrivals if count > 10
        $discount = $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Discount', 'Value' => 1]);
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
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/shop", name="shop")
     */
    public function products(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $prod_photos = $this->getDoctrine()->getRepository(Photo::class)->findAll();

        $pagination = $paginator->paginate(
            $prod_photos, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            45 /*limit per page*/
        );

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'prod_photos'=> $prod_photos,
            'pagination'=> $pagination,
        ]);
    }
    /**
     * @Route("/shop/{id}", name="single_product")
     */
    public function single_product(int $id): Response
    {
        $single = $this->getDoctrine()->getRepository(Photo::class)->findBy(['Product' => $id]);
        if ($single)
        {
            return $this->render('home/single_product.html.twig', [
                'single' => $single
            ]);
        }
        else return $this->render('home/error.html.twig');
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        phpinfo();
        exit;
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
