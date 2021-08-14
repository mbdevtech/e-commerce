<?php

namespace App\Entity\Catalog;

use App\Entity\Account\Profile;
use App\Entity\Transaction\OrdoredProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Catalog\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Catalog\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $excerpt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Photo", mappedBy="product")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Specific", mappedBy="product")
     */
    private $specifics;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $new;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $featured;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Catalog\Discount", mappedBy="product", cascade={"persist", "remove"})
     */
    private $discount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Tax", mappedBy="product")
     */
    private $taxes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Shipping", mappedBy="product")
     */
    private $shippings;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Profile", inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\OrdoredProduct", mappedBy="product")
     */
    private $ordoredProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Review", mappedBy="product")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Attribute::class, mappedBy="product")
     */
    private $attributes;

    public function __construct()
    {
        $this->featured = false;
        $this->photos = new ArrayCollection();
        $this->specifics = new ArrayCollection();
        $this->taxes = new ArrayCollection();
        $this->shippings = new ArrayCollection();
        $this->ordoredProducts = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Specific[]
     */
    public function getSpecifics(): Collection
    {
        return $this->specifics;
    }

    public function addSpecific(Specific $specific): self
    {
        if (!$this->specifics->contains($specific)) {
            $this->specifics[] = $specific;
            $specific->setProduct($this);
        }

        return $this;
    }

    public function removeSpecific(Specific $specific): self
    {
        if ($this->specifics->contains($specific)) {
            $this->specifics->removeElement($specific);
            // set the owning side to null (unless already changed)
            if ($specific->getProduct() === $this) {
                $specific->setProduct(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFeatued(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(bool $new): self
    {
        $this->new = $new;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(Discount $discount): self
    {
        $this->discount = $discount;

        // set the owning side of the relation if necessary
        if ($discount->getProduct() !== $this) {
            $discount->setProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Tax[]
     */
    public function getTaxes(): Collection
    {
        return $this->taxes;
    }

    public function addTax(Tax $tax): self
    {
        if (!$this->taxes->contains($tax)) {
            $this->taxes[] = $tax;
            $tax->setProduct($this);
        }

        return $this;
    }

    public function removeTax(Tax $tax): self
    {
        if ($this->taxes->contains($tax)) {
            $this->taxes->removeElement($tax);
            // set the owning side to null (unless already changed)
            if ($tax->getProduct() === $this) {
                $tax->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Shipping[]
     */
    public function getShippings(): Collection
    {
        return $this->shippings;
    }

    public function addShipping(Shipping $shipping): self
    {
        if (!$this->shippings->contains($shipping)) {
            $this->shippings[] = $shipping;
            $shipping->setProduct($this);
        }

        return $this;
    }

    public function removeShipping(Shipping $shipping): self
    {
        if ($this->shippings->contains($shipping)) {
            $this->shippings->removeElement($shipping);
            // set the owning side to null (unless already changed)
            if ($shipping->getProduct() === $this) {
                $shipping->setProduct(null);
            }
        }

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

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
            $ordoredProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrdoredProduct(OrdoredProduct $ordoredProduct): self
    {
        if ($this->ordoredProducts->contains($ordoredProduct)) {
            $this->ordoredProducts->removeElement($ordoredProduct);
            // set the owning side to null (unless already changed)
            if ($ordoredProduct->getProduct() === $this) {
                $ordoredProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attribute[]
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
            $attribute->setProduct($this);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->contains($attribute)) {
            $this->attributes->removeElement($attribute);
            // set the owning side to null (unless already changed)
            if ($attribute->getProduct() === $this) {
                $attribute->setProduct(null);
            }
        }

        return $this;
    }
}
