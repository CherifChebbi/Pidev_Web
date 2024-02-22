<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     *@Assert\NotBlank(message=" le nom du restaurant est obligatoire ")
     *@Assert\Length(
    * min=5,
    *minMessage = " Enter un nom au minimal 5 caractere  "
      *)
     * @ORM\Column(type="string", length=255)
     */

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    /**
     *@Assert\NotBlank(message=" Entrer la localisation ")
     *@Assert\Length(
    * min=5,
    * max=20,
    * minMessage = "doit etre >=5",
    * maxMessage = "doit etre <=20",
    *minMessage = " Enter un nom au minimal 5 caractere  "
      *)
     * @ORM\Column(type="string", length=255)
     */

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

        /**
     *@Assert\NotBlank(message=" Taper la description")
     *@Assert\Length(
    * min=5,
    * max=100,
    * minMessage = "doit etre >=5",
    * maxMessage = "doit etre <=100",
    *minMessage = " Enter un nom au minimal 5 caractere  "
      *)
     * @ORM\Column(type="string", length=255)
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
   public function __toString()
   {
    return $this->nom;
   }
}
