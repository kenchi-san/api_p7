<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{


    public function __construct()
    {
        $this->roles = ["ROLE_USER"];
        $this->customer = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","user:detail","customer:detail"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:detail","customer:detail"})
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","user:detail","customer:detail"})
     */
    private ?string $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:embed"})
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:detail","customer:detail"})
     */
    private ?string $position_in_the_compagny;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="user", cascade={"persist"})
     * @Groups({"customer:detail"})
     */
    private $customer;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getPositionInTheCompagny(): ?string
    {
        return $this->position_in_the_compagny;
    }

    public function setPositionInTheCompagny(string $position_in_the_compagny): self
    {
        $this->position_in_the_compagny = $position_in_the_compagny;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customer->contains($customer)) {
            $this->customer[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customer->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }



    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->email;
    }
}
