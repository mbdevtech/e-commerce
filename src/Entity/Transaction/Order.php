<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Profile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Sale", inversedBy="ord", cascade={"persist", "remove"})
     */
    private $sale;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Purchase", inversedBy="ord", cascade={"persist", "remove"})
     */
    private $purchase;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\OrdoredProduct", mappedBy="ord")
     */
    private $ordoredProducts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Payment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Shipment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shipment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editedAt;

    public function __construct()
    {
        $this->ordoredProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * @return Collection|OrdoredProduct[]
     */
    public function getOrdoredProducts(): Collection
    {
        return $this->ordoredProducts;
    }

    public function addOrdoredProduct(OrdoredProduct $ordoredProduct): self
    {
        if (!$this->ordoredProducts->contains($ordoredProduct)) {
            $this->ordoredProducts[] = $ordoredProduct;
            $ordoredProduct->setOrd($this);
        }

        return $this;
    }

    public function removeOrdoredProduct(OrdoredProduct $ordoredProduct): self
    {
        if ($this->ordoredProducts->contains($ordoredProduct)) {
            $this->ordoredProducts->removeElement($ordoredProduct);
            // set the owning side to null (unless already changed)
            if ($ordoredProduct->getOrd() === $this) {
                $ordoredProduct->setOrd(null);
            }
        }

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->editedAt;
    }

    public function setEditedAt(\DateTimeInterface $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }
}
