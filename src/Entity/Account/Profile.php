<?php

namespace App\Entity\Account;

use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use App\Entity\Catalog\Product;
use App\Entity\Catalog\Review;
use App\Entity\Messaging\Message;
use App\Entity\Messaging\ReceivedMessage;
use App\Entity\Transaction\Order;
use App\Entity\Transaction\Purchase;
use App\Entity\Transaction\Sale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
// assert
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\ProfileRepository")
 */
class Profile
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
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

     /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\User", inversedBy="profile",cascade={"persist", "remove"})
     */
    private $userid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
     private $phoneNumber;

    /**
      * @ORM\Column(type="string", length=255, nullable=true, unique=true) 
    */

    private $picture;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

     private $cover;
     
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\BillingAddress", mappedBy="profile", cascade={"persist", "remove"})
     */
    private $billingAddress;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\ShippingAddress", mappedBy="profile", cascade={"persist", "remove"})
     */
    private $shippingAddress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Fund", mappedBy="profile")
     */
    private $funds;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Product", mappedBy="profile")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalog\Review", mappedBy="profile")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Blog\Post", mappedBy="profile")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Blog\Comment", mappedBy="profile")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Sale", mappedBy="profile")
     */
    private $sales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Purchase", mappedBy="profile")
     */
    private $purchases;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messaging\Message", mappedBy="profile")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messaging\ReceivedMessage", mappedBy="profile")
     */
    private $receivedMessages;

    public function __construct()
    {
        $this->funds = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->purchases = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getfullName(): ?string
    {
        return $this->fullName;
    }

    public function setfullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function setUserid(User $user)
    {
        $this->userid = $user;
        
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture)
    {
        $this->picture = $picture;

    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

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

    public function getBillingAddress(): ?BillingAddress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(BillingAddress $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        // set the owning side of the relation if necessary
        if ($billingAddress->getProfile() !== $this) {
            $billingAddress->setProfile($this);
        }

        return $this;
    }

    public function getShippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(ShippingAddress $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        // set the owning side of the relation if necessary
        if ($shippingAddress->getProfile() !== $this) {
            $shippingAddress->setProfile($this);
        }

        return $this;
    }

    /**
     * @return Collection|Fund[]
     */
    public function getFunds(): Collection
    {
        return $this->funds;
    }

    public function addFund(Fund $fund): self
    {
        if (!$this->funds->contains($fund)) {
            $this->funds[] = $fund;
            $fund->setProfile($this);
        }

        return $this;
    }

    public function removeFund(Fund $fund): self
    {
        if ($this->funds->contains($fund)) {
            $this->funds->removeElement($fund);
            // set the owning side to null (unless already changed)
            if ($fund->getProfile() === $this) {
                $fund->setProfile(null);
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProfile($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getProfile() === $this) {
                $product->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Order $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setSeller($this);
        }

        return $this;
    }

    public function removeSale(Order $sale): self
    {
        if ($this->sales->contains($sale)) {
            $this->sales->removeElement($sale);
            // set the owning side to null (unless already changed)
            if ($sale->getSeller() === $this) {
                $sale->setSeller(null);
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
            $review->setProfile($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getProfile() === $this) {
                $review->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setProfile($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getProfile() === $this) {
                $post->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProfile($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProfile() === $this) {
                $comment->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Purchase[]
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setProfile($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        if ($this->purchases->contains($purchase)) {
            $this->purchases->removeElement($purchase);
            // set the owning side to null (unless already changed)
            if ($purchase->getProfile() === $this) {
                $purchase->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setProfile($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->removeElement($sentMessage);
            // set the owning side to null (unless already changed)
            if ($sentMessage->getProfile() === $this) {
                $sentMessage->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReceivedMessage[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(ReceivedMessage $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setProfile($this);
        }

        return $this;
    }

    public function removeReceivedMessage(ReceivedMessage $receivedMessage): self
    {
        if ($this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getProfile() === $this) {
                $receivedMessage->setProfile(null);
            }
        }

        return $this;
    }

}
