<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Stripe;

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
     * @Route("/checkout", name="shopping_checkout")
     */
    public function checkout(SessionInterface $session, ProductRepository $repo, CartService $cs)
    {
        $cart = $session->get('cart', []);
        // call the service functions
        return $this->render('/cart/checkout.html.twig', [
            'cart' => $cart, 
            'products'=> $cs->List($session, $repo),
            'total' => $cs->Total($session, $repo)        
        ]);
    }
    /**
     * @Route("/checkout/stripe-form", name="checkout_form", methods="GET")
     */
    public function form(): Response
    {
        return $this->redirect('https://buy.stripe.com/test_4gw17z7C84YgbKw8ww');
    }
    /**
     * @Route("/checkout/create-charge", name="checkout_charge")
     */
    public function createCharge(Request $request)
    {

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $token = $request->request->get('stripeToken');
        dd($token);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                //"customer" =>  $customer['id'] ,
                "email" => 'mahfoud_bousba@yahoo.com',
                "source" => $token,
                "description" => "Binaryboxtuts Payment Test"
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('shopping_checkout', [], Response::HTTP_SEE_OTHER);
    }
}
