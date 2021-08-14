<?php

namespace App\Entity\Transaction;

use App\Entity\Catalog\Product;
use App\Entity\Catalog\Shipping;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\OrdoredProductRepository")
 */
class OrdoredProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction\Order", inversedBy="ordoredProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ord;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Catalog\Product", inversedBy="ordoredProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Catalog\Shipping", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shipping;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrd(): ?Order
    {
        return $this->ord;
    }

    public function setOrd(?Order $ord): self
    {
        $this->ord = $ord;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    public function setShipping(Shipping $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }
}
