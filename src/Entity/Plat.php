<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     *@Assert\NotBlank(message=" le nom du plat est obligatoire ")
     *@Assert\Length(
    * min=5,
    *minMessage = " Enter un nom au minimal 5 caractere  "
      *)
     * @ORM\Column(type="string", length=255)
     */

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    /**
     *@Assert\NotBlank(message=" le prix du plat est obligatoire ")
     *@Assert\Length(
    * min=5,
    *minMessage = " Enter un nom au minimal 5 caractere  "
      *)
     * @ORM\Column(type="string", length=255)
     */

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $prix = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getRestaurantId(): ?Restaurant
    {
        return $this->restaurant_id;
    }

    public function setRestaurantId(?Restaurant $restaurant_id): static
    {
        $this->restaurant_id = $restaurant_id;

        return $this;
    }
    public function __toString()
    {
        return $this->restaurant_id;
    }
    
}
