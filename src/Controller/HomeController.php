<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Photo;
use App\Entity\Specification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('home/shop.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/shop/{id}", name="single_product")
     */
    public function single_product(int $id): Response
    {
        $single = $this->getDoctrine()->getRepository(Photo::class)->findBy(['Product' => $id]);
        //dd($single);
        return $this->render('home/single_product.html.twig', [
            'single' => $single
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
    /**
     * @Route("/cart", name="cart")
     */
    public function cart(): Response
    {
        return new Response(" I am a cart page");
        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }
}
