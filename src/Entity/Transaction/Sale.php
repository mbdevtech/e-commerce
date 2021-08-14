<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Profile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\SaleRepository")
 */
class Sale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Profile", inversedBy="sales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transaction\Order", mappedBy="sale", cascade={"persist", "remove"})
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
        $newSale = null === $ord ? null : $this;
        if ($ord->getSale() !== $newSale) {
            $ord->setSale($newSale);
        }

        return $this;
    }
}
