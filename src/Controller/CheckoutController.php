<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
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
        return $this->render('/cart/checkout.html.twig', [
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
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        $token = $request->$_REQUEST->get('stripeToken');
        dd($token);
        Stripe\Charge::create([
            "amount" => 5 * 100,
            "currency" => "usd",
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
