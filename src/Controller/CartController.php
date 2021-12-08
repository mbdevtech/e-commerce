<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
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
    public function index(SessionInterface $session, PhotoRepository $photoRepository)
    {
        $cart = $session->get('cart', []);
        // Add products to the Cart
        $productsCart = [];
        foreach ($cart as $id => $quantity) {
            $productsCart[] = [
                'product' => $photoRepository->findByProductId($id),
                //'product' => $productRepository->find($id),
                'quantity' => $quantity,
            ];
        }
        //dd($productsCart);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'productsCart' => $productsCart,
        ]);
    }

    /**
     * @Route("/add/{id}/", name="cart_add")
     */
    public function add(int $id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        // find the product to add
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->Set('cart', $cart);
        //end test
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route("/remove/{id}/", name="cart_remove")
     */
    public function remove(int $id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        // remoce the product if exist
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->Set('cart', $cart);

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
