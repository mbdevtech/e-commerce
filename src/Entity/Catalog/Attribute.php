<?php

namespace App\Entity\Catalog;

use App\Repository\Catalog\AttributeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 */
class Attribute
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attrName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attrValue;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="attributes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttrName(): ?string
    {
        return $this->attrName;
    }

    public function setAttrName(string $attrName): self
    {
        $this->attrName = $attrName;

        return $this;
    }

    public function getAttrValue(): ?string
    {
        return $this->attrValue;
    }

    public function setAttrValue(string $attrValue): self
    {
        $this->attrValue = $attrValue;

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
}
