<?php
namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *     "self",
 *     href=@Hateoas\Route(
 *          "customer"
 *     )
 *
 * )
 * @Hateoas\Relation(
 *     "show",
 *     href=@Hateoas\Route(
 *          "detail_customer",
 *     parameters = { "id" = "expr(object.getId())" },
 *
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href=@Hateoas\Route(
 *          "delete_customer",
 *     parameters = { "id" = "expr(object.getId())" },
 *
 *     )
 * )
 * @Hateoas\Relation(
 *     "add",
 *     href=@Hateoas\Route(
 *          "add_customer",
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "user",
 *     href = "expr('/api/user/' ~ object.getUser().getId())",
 *     embedded = "expr(object.getUser())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getUser() === null)",groups={"customer:detail","customer:list"} )
 *
 * )
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{

    public function __construct($membership_number)
    {

        $this->membership_number = $membership_number;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Exclude
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Groups({"customer:list","customer:detail","customer:add"})
     *
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Groups({"customer:list","customer:detail","customer:add"})
     */
    private ?string $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @Groups({"customer:detail","customer:add"})
     */
    private ?string $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Groups({"customer:detail","customer:add"})
     */
    private ?string $address;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Groups({"customer:detail","customer:add"})
     */
    private ?int $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:detail","customer:add"})
     */
    private ?string $membership_number;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customer")
     * @Serializer\Exclude
     */
    private ?User $user;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMembershipNumber(): ?string
    {
        return $this->membership_number;
    }

    public function setMembershipNumber(string $membership_number): self
    {
        $this->membership_number = $membership_number;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}