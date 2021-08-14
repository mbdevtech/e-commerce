<?php

namespace App\Entity\Account;
//----------  Assert and validation activation
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//---------------------------
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\UserRepository")
 * @UniqueEntity(fields = {"username"}, message = "Username est deja utilise")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6",minMessage = "Le mot de passe doit etre 6 caracteres au minimum.")
     */
    private $password;
    /**
     *  @Assert\EqualTo(propertyPath="password", message="Il faut entrer le meme mot de passe")
    */
    public $confirm;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Account\Role", inversedBy="users")
     */
    public $role;
    /**
    * @ORM\Column(type="json")
    */
    private $roles  ;

    function updateRoles($role) {
        $this->roles = [];
        $this->roles[] = $role;
    } 
        
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Account\Profile", mappedBy="userid", cascade={"persist", "remove"})
     */
    private $profile;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setProfile(Profile  $p)
    {
        $this->profile = $p;
    }

    public function getSalt()
    {
        # code...
    }

    public function eraseCredentials()
    {
        # code...
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles():array
    {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeUser($this);
        }

        return $this;
    }
}
