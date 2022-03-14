<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Specification;
use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('home/index.html.twig', [
            'newarrival' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'New Arrival', 'Value' => 1]),
            'bestseller' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Best Seller', 'Value' => 1]),
            'hotdeal' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Hot Deal', 'Value' => 1]),
            'featured' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Featured', 'Value' => 1]),
            'topdeal' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Top Deal', 'Value' => 1]),
            'discount' => $this->getDoctrine()->getRepository(Specification::class)->findBy(['Name' => 'Discount', 'Value' => 1]),
            'prod_photos' => $this->getDoctrine()->getRepository(Photo::class)->findAll()
        ]);
    }
    /**
     * @Route("/list", name="product_list")
     */
    public function list(): Response
    {
        return $this->render('home/list.html.twig', [
            'product_list' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
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
    public function products(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $prod_photos = $this->getDoctrine()->getRepository(Photo::class)->findAll();

        return $this->render('home/shop.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'prod_photos'=> $prod_photos
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
