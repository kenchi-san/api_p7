<?php

namespace App\Entity;

use App\Repository\ProductPhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * *
 * @ORM\Entity(repositoryClass=ProductPhoneRepository::class)
 */
class ProductPhone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Exclude()
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phone:list","phone:detail"})
     *
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"phone:detail"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"phone:detail"})
     */
    private ?float $price;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
}
