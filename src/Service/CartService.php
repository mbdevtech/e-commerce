<?php
namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class CartService
{

 public function List(SessionInterface $session, ProductRepository $productRepo): array
 {
      $cart = $session->get('cart', []);
      // Add products to the Cart
      $productsCart = [];
      foreach ($cart as $id => $quantity) {
            $productsCart[] = [
                'product' => $productRepo->find($id),
                'quantity' => $quantity,
            ];
      }
      return $productsCart;
 }

public function Add(int $id, SessionInterface $session)
{
        $cart = $session->get('cart', []);
        // find the product to add
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->Set('cart', $cart);
        return $cart;
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
public function Total(SessionInterface $session, ProductRepository $productRepo)
{
        $cart = $session->get('cart', []);
        // Add products to the Cart
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $subtotal = $productRepo->find($id)->getPrice() * $quantity;
            $total = (float)($total + $subtotal);
        }
        return $total;
}
}