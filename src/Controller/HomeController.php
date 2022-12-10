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
    /**
     * @Route("/", name="home")
     */
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
     * @Route("/discount", name="discount")
     */
    public function discounts(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $discounts = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Discount']);
        
        $pagination = $paginator->paginate(
            $discounts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );
        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $discounts,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/newarrival", name="newarrival")
     */
    public function newarrivals(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $newarrivals = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'New Arrival']);

        $pagination = $paginator->paginate(
            $newarrivals, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $newarrivals,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/bestseller", name="bestseller")
     */
    public function bestseller(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Best Seller']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/topdeal", name="topdeal")
     */
    public function topdeal(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Top Deal']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/hotdeal", name="hotdeal")
     */
    public function hotdeal(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Hot Deal']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/featured", name="featured")
     */
    public function bestsellers(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Featured']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
