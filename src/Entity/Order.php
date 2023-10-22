<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EditedAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderedProduct::class, mappedBy="OrderId", orphanRemoval=true,cascade={"persist"})
     * 
     */
    private $orderedProducts;

    public function __construct()
    {
        $this->orderedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->EditedAt;
    }

    public function setEditedAt(\DateTimeInterface $EditedAt): self
    {
        $this->EditedAt = $EditedAt;

        return $this;
    }

    /**
     * @return Collection|OrderedProduct[]
     */
    public function getOrderedProducts(): Collection
    {
        return $this->orderedProducts;
    }

    public function addOrderedProduct(OrderedProduct $orderedProduct): self
    {
        if (!$this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts[] = $orderedProduct;
            $orderedProduct->setOrder($this);
        }

        return $this;
    }

    public function removeOrderedProduct(OrderedProduct $orderedProduct): self
    {
        if ($this->orderedProducts->removeElement($orderedProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderedProduct->getOrder() === $this) {
                $orderedProduct->setOrder(null);
            }
        }

        return $this;
    }
}
