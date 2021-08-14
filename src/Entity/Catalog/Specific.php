<?php

namespace App\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Catalog\SpecificRepository")
 */
class Specific
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Catalog\Product", inversedBy="specifics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
     private $attrName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attrValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttrName(): ?string
    {
        return $this->attrName;
    }

    public function setAttrName(string $name)
    {
        $this->attrName = $name;

        //return $this;
    }

    public function getAttrValue(): ?string
    {
        return $this->attrValue;
    }

    public function setAttrValue(string $value) 
    {
        $this->attrValue = $value;

        //return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product) : self
    {
        $this->product = $product;

        return $this;
    }
}
