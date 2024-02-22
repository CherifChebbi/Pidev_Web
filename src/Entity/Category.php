<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column(length: 255)]
    private ?string $hotel = null;

    #[ORM\Column(length: 255)]
    private ?string $maison_dhote = null;

    #[ORM\ManyToOne(inversedBy: 'category')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hebergement $hebergement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   
   

    public function getHotel(): ?string
    {
        return $this->hotel;
    }

    public function setHotel(string $hotel): static
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getMaisonDhote(): ?string
    {
        return $this->maison_dhote;
    }

    public function setMaisonDhote(string $maison_dhote): static
    {
        $this->maison_dhote = $maison_dhote;

        return $this;
    }

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): static
    {
        $this->hebergement = $hebergement;

        return $this;
    }
}
