<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop/", name="shop-grid")
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
            return $this->render('shop/shop-grid.html.twig', [
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'pagination' => $pagination,
                'breadcrumb' => 'Shop-grid'
            ]);  
    
    }

    /**
     * @Route("/shop/product/{id}", name="single_product")
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
            return $this->render('shop/single_product.html.twig', [
                'single' => $single,
                'product' => $product,
                'same' => $same
            ]);
        } else return $this->render('home/error.html.twig');
    }

    /**
     * @Route("/shop/detail/{id}", name="detail_product")
     */
    public function detail_product(int $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        // Consider only 15 products of the same category
        $same    = $this->getDoctrine()->getRepository(Product::class)->findBy(['Category' => $product->getCategory()]);
        $nbitems = count($same);
        $same = ($nbitems < 15 ? $same : array_slice($same, $nbitems - 15, $nbitems - 1));

        if ($product) {
            return $this->render('shop/detail-product.html.twig', [
                'product' => $product,
                'same' => $same
            ]);
        } else return $this->render('home/error.html.twig');
    }
    /**
     * @Route("/shop/category/{category}/", name="single_category")
     */
    public function single_category(string $category, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $mycategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Name' => $category]);
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $products    = $this->getDoctrine()->getRepository(Product::class)->findBy(['Category' => $mycategory->getId()]);

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );
        return $this->render('shop/shop-grid.html.twig', [
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'pagination' => $pagination,
                'breadcrumb' => $category
            ]);
 
    }
    /**
     * @Route("/shop/brand/{brand}", name="single_brand")
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

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'pagination' => $pagination,
            'breadcrumb' => $brand
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
        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $discounts,
            'pagination' => $pagination,
            'breadcrumb' => 'Discount'
        ]);
    }

    /**
     * @Route("/shop/newarrival", name="newarrival")
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

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $newarrivals,
            'pagination' => $pagination,
            'breadcrumb' => 'New Arrival'
        ]);
    }

    /**
     * @Route("/shop/bestseller", name="bestseller")
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

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
            'breadcrumb' => 'Best Seller'
        ]);
    }

    /**
     * @Route("/shop/topdeal", name="topdeal")
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

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
            'breadcrumb' => 'Top Deal'
        ]);
    }

    /**
     * @Route("/shop/hotdeal", name="hotdeal")
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

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
            'breadcrumb' => 'Hot Deal'
        ]);
    }

    /**
     * @Route("/shop/featured", name="featured")
     */
    public function featured(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        $bestsellers = $this->getDoctrine()->getRepository(Product::class)->findBy(['Specification' => 'Featured']);

        $pagination = $paginator->paginate(
            $bestsellers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('shop/shop-grid.html.twig', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $bestsellers,
            'pagination' => $pagination,
            'breadcrumb' => 'Featured'
        ]);
    }
}
