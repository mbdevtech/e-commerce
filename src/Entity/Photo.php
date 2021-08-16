<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Caption;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->Caption;
    }

    public function setCaption(string $Caption): self
    {
        $this->Caption = $Caption;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->Url;
    }

    public function setUrl(string $Url): self
    {
        $this->Url = $Url;

        return $this;
    }
}
