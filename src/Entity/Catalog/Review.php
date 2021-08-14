<?php

namespace App\Entity\Catalog;

use App\Entity\Account\Profile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Catalog\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Catalog\Product", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Profile", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profile;
    // add comment and profile info
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

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
