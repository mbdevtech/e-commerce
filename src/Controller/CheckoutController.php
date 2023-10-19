<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe;

class CheckoutController extends AbstractController
{

    #[Route('/checkout', name: 'shopping_checkout')]
    public function index(SessionInterface $session, ProductRepository $repo, CartService $cs)
    {
        $cart = $session->get('cart', []);

        // call the service functions
        return $this->render('/checkout/checkout3.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'cart' => $cart,
            'products' => $cs->List($session, $repo),
            'total' => $cs->Total($session, $repo)
        ]);
    }

    #[Route('/checkout/stripe-form', name: 'stripe_form', methods:'GET')]
    public function form(): Response
    {
        return $this->redirect('https://buy.stripe.com/test_4gw17z7C84YgbKw8ww');
    }

    #[Route('/checkout/create-charge', name: 'stripe_charge')]
    public function createCharge(SessionInterface $session, ProductRepository $repo, CartService $cs, Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $token = $request->request->get('stripeToken');
        
        Stripe\Charge::create(
            [
                'amount' => $cs->Total($session, $repo) * 100,
                'currency' => 'usd',
                'source' => $token,
                'description' => 'My First Test Charge (created for API docs)',            ]
            );
        // empty card if payment success
        $cs->Empty($session);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('shopping_checkout', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/checkout/stripe', name: 'stripe-checkout')]
    public function createCheckout(SessionInterface $session, ProductRepository $repo, CartService $cs): Response
    {

        $products = [
            [
                'name' => 'product 1',
                'price' => 2222,
                'quantity' => 2
            ],
            [
                'name' => 'product 2',
                'price' => 990,
                'quantity' => 2
            ],
            [
                'name' => 'product 3',
                'price' => 5555,
                'quantity' => 1
            ],
            [
                'name' => 'product 4',
                'price' => 766,
                'quantity' => 3
            ]
        ];
       
        $items = $cs->List($session, $repo);

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [array_map(fn (array $product) =>
            [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product['product']->getPrice() * 100,
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'description' => 'Comfortable cotton t-shirt',
                        'images' => ['layout/images/product/large-size/2.jpg'],
                    ],
                ],
                'quantity' => $product['quantity'],
            ], $items)],

            'mode' => 'payment',
            'billing_address_collection' => "required",
            'shipping_address_collection' => [
                'allowed_countries' => ['CA', 'US']
            ],
            'success_url' => 'https://localhost:8000/checkout/success',
            'cancel_url' => 'https://localhost:8000/checkout/cancel',
        ]);
        //dd($session);
        return $this->redirect($session->url, 303);
    }

    #[Route('/checkout/success', name: 'success')]
    public function success(): Response
    {
        return $this->render('checkout/success.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);
    }

    #[Route('/checkout/cancel', name: 'cancel')]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);
    }
   
   
    public function createSession(SessionInterface $session, ProductRepository $repo, CartService $cs, Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $token = $request->request->get('stripeToken');

        // Create new Checkout Session for the order
        // For full details see https:#stripe.com/docs/api/checkout/sessions/create
        $checkout_session = Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Guitar lesson',
                'images' => 'ddddd.png',//['https://i.ibb.co/2PNy7yB/guitar.png'],
                'quantity' => 2,
                'amount' => 400,
                'currency' => 'USD',
            ]],
            // ?session_id={CHECKOUT_SESSION_ID} means the redirect will have the session ID set as a query para
            'success_url' => 'localhost:8000'. '/success.html?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'localhost:8000' . '/canceled.html',
        ]);
        dd($checkout_session);
    return($checkout_session);
    }

}