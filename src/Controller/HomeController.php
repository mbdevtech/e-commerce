<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Product;
use Doctrine\ORM\Mapping\Id;
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

    // To do:
    // - Add thumbnail, specification fields to Product 
    // - Remove specification Model 
    // - Fix code related to theses changes

    /**
     * @Route("/shop", name="shop")
     */
    public function products(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'pagination'=> $pagination,
        ]);
    }

    /**
     * @Route("/shop/discount", name="discount")
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

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $discounts,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/shop/newarrival", name="new")
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

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $newarrivals,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/shop/best", name="best")
     */
    public function bestsellers(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Best Seller']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/shop/{id}", name="single_product")
     */
    public function single_product(int $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        // Consider only 15 products of the same category
        $same    = $this->getDoctrine()->getRepository(Product::class)->findBy(['Category' => $product->getCategory()]);
        $nbitems = count($same);
        $same = ($nbitems < 15 ? $same : array_slice($same, $nbitems - 15, $nbitems - 1));
        // extract all the product photos
        $single  = $this->getDoctrine()->getRepository(Photo::class)->findBy(['Product' => $id]);

        if ($product)
        {
            return $this->render('home/single_product.html.twig', [
                'single' => $single,
                'product'=> $product,
                'same' => $same
            ]);
        }
        else return $this->render('home/error.html.twig');
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
