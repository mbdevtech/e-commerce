<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ProductRepository;
use App\Entity\User;
use App\Service\CartService;
use App\Service\CheckoutService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe;
//use Symfony\Component\Mailer\MailerInterface;

class CheckoutController extends AbstractController
{   

    #[Route('/checkout', name: 'shopping_checkout')]
    public function index(SessionInterface $session, ProductRepository $repo, CartService $cs)
    {
        $cart = $session->get('cart', []);

        // call the service functions
        return $this->render('/checkout/checkout.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'cart' => $cart,
            'products' => $cs->List($session, $repo),
            'total' => $cs->Total($session, $repo)
        ]);
    }

    // This Route allow to implement a Stripe Custom Checkout
    #[Route('/checkout/create-charge', name: 'stripe_charge')]
    // public function createCharge(SessionInterface $session, ProductRepository $repo, CartService $cs, Request $request)
    // {
    //     Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    //     $token = $request->request->get('stripeToken');
        
    //     Stripe\Charge::create(
    //         [
    //             'amount' => $cs->Total($session, $repo) * 100,
    //             'currency' => 'usd',
    //             'source' => $token,
    //             'description' => 'My First Test Charge (created for API docs)',            ]
    //         );
    //     // empty cart if payment success
    //     $cs->Empty($session);
    //     $this->addFlash(
    //         'success',
    //         'Payment Successful!'
    //     );
    //     return $this->redirectToRoute('shopping_checkout', [], Response::HTTP_SEE_OTHER);
    // }

    // This Route allow to implement a Stripe hosted Checkout
    #[Route('/checkout/stripe', name: 'stripe-checkout')]
    public function createCheckout(SessionInterface $session, ProductRepository $repo, 
        CartService $cs, CheckoutService $checkout): Response
    {
        // cart line items
        $items = $cs->List($session, $repo);
       
        // extract a user for test purpose
        $user = $this->getUser();
           // $checkout->getManager()->getRepository(User::class)->find(12));
        if ($user == null)   
            return $this->redirectToRoute("user_login");
           ///////// add a flag to come back to checkout after login
        
        // create the order by calling the service
        $order = $checkout->orderAdd($user, $items);
        
        // create the payment by calling the service
        $payment = $checkout->paymentAdd($order, 'card', $cs->Total($session, $repo));

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [array_map(fn (array $product) =>
            [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product['product']->getPrice() * 100,
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'description' => $product['product']->getDescription(),
                        ],
                ],
                'quantity' => $product['quantity'],
            ], $items)],

            'mode' => 'payment',
            'billing_address_collection' => "required",
            'shipping_address_collection' => [
                'allowed_countries' => ['CA', 'US']
            ],
            // get dynamic url from $_SERVER ot HTTP ewquest
            'success_url' => 'http://localhost:8000/checkout/success?session_id={CHECKOUT_SESSION_ID}&order_id='.$order->getId(),
            //'success_url' => 'https://localhost:8000/checkout/success'.'/' . $order->getId(),
            'cancel_url' => 'http://localhost:8000/checkout/cancel',
        ]);

        // redirect to stripe hosted checkout page
        return $this->redirect($checkout_session->url, 303);
    }

    #[Route('/checkout/success', name: 'success')]
    public function success(SessionInterface $session, CartService $cs, ProductRepository $repo, 
            CheckoutService $checkout, MailerService $ms): Response
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        // update the order status
        $checkout->orderUpdate($_GET['order_id'], 'paid');

        // update the payment status
        $order = $checkout->getManager()->getRepository(Order::class)->find($_GET['order_id']);
        $checkout->paymentUpdate($order->getId(), 'success');

        $stripe_session = Stripe\Checkout\Session::retrieve($_GET['session_id']);
        $customer = Stripe\Customer::retrieve($stripe_session->customer);

        // get products list items
        $items = $cs->List($session, $repo);

        // send confirmation mail to customer with items list
        $ms->twigEmailSend(2,$customer->email, $customer->name,$items);

        // empty cart after checkout success
        $cs->Empty($session);

        return $this->render('checkout/success.html.twig', [
            'customer_email' => $customer->email,
            'order_id' => $_GET['order_id']
        ]);
    }


    #[Route('/checkout/cancel', name: 'cancel')]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);
    }
 
}