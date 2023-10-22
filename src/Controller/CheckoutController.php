<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\CartService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe;

class CheckoutController extends AbstractController
{   
    private ManagerRegistry $manager;

    public function __construct(ManagerRegistry $mr)
    {
        $this->manager = $mr;
    }

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

    // This Route allow to implement a Stripe Custom Checkout
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

    // This Route allow to implement a Stripe hosted Checkout
    #[Route('/checkout/stripe', name: 'stripe-checkout')]
    public function createCheckout(SessionInterface $session, ProductRepository $repo, CartService $cs): Response
    {
     
        $items = $cs->List($session, $repo);

        // create order for each product in line items
        $order = new Order();
        // add ordered products
        foreach ($items as $item) {       
            $orderedProduct = new OrderedProduct();
            $orderedProduct->setProduct($item['product']);
            $orderedProduct->setQuantity($item['quantity']);
            $orderedProduct->setOrder($order);
            $order->addOrderedProduct($orderedProduct);
        }
        // extract a user for test purpose
        /* todo : 
            - consider connected user if not use admin user
            - create a payment record
            - send email to the client when the payment succeed
            - improve the code by creating a checkout service       
        
        */
        $user = (($this->getUser() != null)? $this->getUser():
            $this->manager->getRepository(User::class)->find(12));
        // set order proprieties
        $order->setUser($user);
        $order->setStatus("Pending");   // 3 possible status : pending, paid or canceled
        $order->setEditedAt(new DateTime());
        // save to db
        $this->manager->getManager()->persist($order);
        $this->manager->getManager()->flush();
        // dd($order);

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
   
}