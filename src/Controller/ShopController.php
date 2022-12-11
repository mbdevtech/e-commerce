<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop-grid", name="shop-grid")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );
            return $this->render('home/shop-grid.html.twig', [
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'pagination' => $pagination,
            ]);
    }
    /**
     * @Route("/shop-list", name="shop-list")
     */
    public function list(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );
        return $this->render('home/shop-list.html.twig', [
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
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

        if ($product) {
            return $this->render('home/single_product.html.twig', [
                'single' => $single,
                'product' => $product,
                'same' => $same
            ]);
        } else return $this->render('home/error.html.twig');
    }
    /**
     * @Route("/{category}", name="single_category")
     */
    public function single_category(string $category, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mycategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Name' => $category]);
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products    = $this->getDoctrine()->getRepository(Product::class)->findBy(['Category' => $mycategory->getId()]);
      
        $nbitems = count($products);
        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/brand/{brand}", name="single_brand")
     */
    public function single_brand(string $brand, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mybrand = $this->getDoctrine()->getRepository(Brand::class)->findOneBy(['name' => $brand]);
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        //$products    = $this->getDoctrine()->getRepository(Product::class)->findBy(['brand' => $mybrand->getName()]);
        $products = $mybrand->getProducts();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }
}
