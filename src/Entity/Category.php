<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Excerpt;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Icon;

    /**
     * @ORM\Column(type="integer")
     */
    private $ParentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->Excerpt;
    }

    public function setExcerpt(string $Excerpt): self
    {
        $this->Excerpt = $Excerpt;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->Icon;
    }

    public function setIcon(string $Icon): self
    {
        $this->Icon = $Icon;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->ParentId;
    }

    public function setParentId(int $ParentId): self
    {
        $this->ParentId = $ParentId;

        return $this;
    }
}
