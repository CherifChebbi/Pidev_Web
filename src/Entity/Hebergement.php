<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter valid data')]

    public  ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter valid data')]

    private ?string $description = null;

    

    #[ORM\OneToMany(mappedBy: 'hebergement', targetEntity: Reservation::class, cascade: ["remove"])]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'hebergements')]
    private ?Category_h $categorie = null;

    public function __construct()
    {
       
        $this->reservations = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation_h $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setHebergement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation_h $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getHebergement() === $this) {
                $reservation->setHebergement(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Category_h
    {
        return $this->categorie;
    }

    public function setCategorie(?Category_h $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
