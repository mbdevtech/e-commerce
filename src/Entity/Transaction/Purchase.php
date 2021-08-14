<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Profile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\PurchaseRepository")
 */
class Purchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Profile", inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Order", mappedBy="purchase", cascade={"persist", "remove"})
     */
    private $ord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getOrd(): ?Order
    {
        return $this->ord;
    }

    public function setOrd(?Order $ord): self
    {
        $this->ord = $ord;

        // set (or unset) the owning side of the relation if necessary
        $newPurchase = null === $ord ? null : $this;
        if ($ord->getPurchase() !== $newPurchase) {
            $ord->setPurchase($newPurchase);
        }

        return $this;
    }
}
