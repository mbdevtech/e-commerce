<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderedProduct;
use Stripe\Stripe;

Class StripeService
{

    private $privateKey;

    public function __construct() {
        $this->privateKey = $_ENV['STRIPE_SECRET'];
    }

    public function PaymentIntent(Product $product) {
        Stripe::SetApiKey($this->privateKey);
        return \Stripe\PaymentIntent::create([
            'amount'=>1999,
            'currency'=>'usd',
            'payment_method_types' => ['card'],
            ]);
    }

    public function Payment($amount, $currency, $description, array $stripeParameter)
    {
        Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if (isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if ($stripeParameter['stripeIntentId'] === 'succeeded'){
            // todo
        } else{
            $payment_intent->cancel();
        }
        return $payment_intent;
    }

    public function stripe(array $stripeParameter, Product $product){
        return $this->Payment(1999, 'usd', $product->getName(), $stripeParameter);
        
    }

}
    


?>