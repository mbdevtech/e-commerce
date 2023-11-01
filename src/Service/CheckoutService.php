<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Payment;
use App\Entity\User;
use DateTime;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CheckoutService
{
    private ManagerRegistry $manager;

    public function __construct(ManagerRegistry $mr)
    {
        $this->manager = $mr;
    }
    // This service allow us to :
    //    1- Create an order
    //    2- Update an order --> paid
    //    3- Add record in payments
    //    4- Update product inventory
    //    5- List all orders, payments
   
    public function getManager() {
        return $this->manager;
    }

    public function orderList(OrderRepository $orderRepo): array
    {
        return $orderRepo->findAll();
    }

    public function orderAdd(User $user, $products)
    {
        // create order for each product in line items
        $order = new Order();
        // add ordered products
        foreach ($products as $item) {
            $orderedProduct = new OrderedProduct();
            $orderedProduct->setProduct($item['product']);
            $orderedProduct->setQuantity($item['quantity']);
            $orderedProduct->setOrder($order);
            $order->addOrderedProduct($orderedProduct);
        }
        // set order proprieties
        $order->setUser($user);
        $order->setStatus("Pending");   // 3 possible status : pending, paid or canceled
        $order->setEditedAt(new DateTime());
        // save to db
        $this->manager->getManager()->persist($order);
        $this->manager->getManager()->flush();
        // dd($order);
        return($order);
    }
    public function orderRemove(int $id)
    {
        $order = $this->manager->getRepository(Order::class)->find($id);
        $this->manager->getManager()->remove($order);
        $this->manager->getManager()->flush();
    }
    public function orderUpdate(int $id, string $status)
    {
        $order = $this->manager->getRepository(Order::class)->find($id);
        $order->setStatus($status);
        $this->manager->getManager()->persist($order);
        $this->manager->getManager()->flush();
    }
    public function paymentList(PaymentRepository $paymentRepo)
    {
        return $paymentRepo->findAll();
    }
    public function paymentAdd(Order $order, string $type, float $amount)
    {
        // create a payment according to call parameters
        $payment = new Payment();

        $payment->setOrder($order);
        $payment->setType($type);
        $payment->setAmount($amount);
        $payment->setStatus("Pending");   // 3 possible status : pending, paid or canceled
        $payment->setDate(new DateTime());
        // save to db
        $this->manager->getManager()->persist($payment);
        $this->manager->getManager()->flush();
        // dd($order);
        return ($payment);

    }

    public function paymentRemove(int $id)
    {
        $payment = $this->manager->getRepository(Payment::class)->find($id);
        $this->manager->getManager()->remove($payment);
        $this->manager->getManager()->flush();
    }
    public function paymentUpdate(int $orderid, string $status)
    {
        // find the record to update 
        $payment = $this->manager->getRepository(Payment::class)->findBy(['Order' => $orderid]);
        // only one record should be retrieved
        $payment[0]->setStatus($status);
        $this->manager->getManager()->persist($payment[0]);
        $this->manager->getManager()->flush();

    }
    public function emailSend(MailerInterface $mailer, string $email)
    {
        $mail = (new Email())
            ->from('mahfoudbousba2@gmail.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($mail);

    }
}
