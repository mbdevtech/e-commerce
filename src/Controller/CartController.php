<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */

class CartController extends AbstractController
{
    /**
     * @Route("/", name="shopping_cart")
     */
    public function index(SessionInterface $session, ProductRepository $repo, CartService $cs)
    {
        $cart = $session->get('cart', []);
        // call the service functions
        return $this->render('cart/index.html.twig', [
            'cart' => $cart, 
            'productsCart'=> $cs->List($session, $repo),
            'total' => $cs->Total($session, $repo)
        ]);
    }

    /**
     * @Route("/add/{id}/", name="cart_add")
     */
    public function add(int $id, SessionInterface $session, CartService $cs)
    {
        // call the service add function
        $cs->Add($id,$session);
        //redirect to list
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route("/remove/{id}/", name="cart_remove")
     */
    public function remove(int $id, SessionInterface $session, CartService $cs)
    {
        // call the service remove function
        $cs->Remove($id, $session);
        //redirect to list
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route("/checkout/{id}", name="shopping_checkout")
     */
    public function checkout($id)
    {
        return $this->render('shopping/cart/checkout.html.twig', [
            'controller_name' => 'CartController',
            'id' => $id
        ]);
    }
}
