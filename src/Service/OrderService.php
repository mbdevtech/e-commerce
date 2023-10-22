<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{
    // This service allow us to :
    //    1- Create an order
    //    2- Update an order --> paid
    //    3- Add record in payments
    //    4- Update product inventory
    //    5- List all orders, payments

    public function orderList(OrderRepository $orderRepo): array
    {
        return $orderRepo->findAll();;
    }

    public function orderAdd(OrderRepository $orderRepo)
    {

    }
    public function Remove(int $id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        // remoce the product if exist
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->Set('cart', $cart);
    }
    public function Empty(SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        unset($cart);
        $session->Set('cart', null);
    }
    public function Total(SessionInterface $session, ProductRepository $productRepo)
    {
        $cart = $session->get('cart', []);
        // Add products to the Cart
        $total = 0;
        // if not empty
        if ($cart) {
            foreach ($cart as $id => $quantity) {
                $subtotal = $productRepo->find($id)->getPrice() * $quantity;
                $total = (float)($total + $subtotal);
            }
        }
        return $total;
    }
}
