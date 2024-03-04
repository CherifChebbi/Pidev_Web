<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    #[Assert\Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\- ]+$/',
        message: 'Le nom ne peut contenir que des lettres, des tirets et des espaces'
        )]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de début ne peut pas être vide')]
    #[Assert\Type('\DateTimeInterface', message: 'La date de début doit être une date valide')]    
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de fin ne peut pas être vide')]
    #[Assert\Type('\DateTimeInterface', message: 'La date de fin doit être une date valide')]
    #[Assert\GreaterThan(propertyPath: "date_debut", message: "La date de fin doit être postérieure à la date de début")]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le lieu ne peut pas être vide')]
    #[Assert\Length(max: 255, maxMessage: 'Le lieu ne peut pas dépasser {{ limit }} caractères')]
    private ?string $lieu = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le prix ne peut pas être vide')]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être supérieur à 0')]
    private ?float $prix = null;
    

    #[ORM\Column(length: 255)]
    private ?string $image_event = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $idCategory = null;

    #[ORM\OneToMany(mappedBy: 'id_event', targetEntity: ReservationEvent::class)]
    public Collection $reservationEvents;

    public function __construct()
    {
        $this->reservationEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImageEvent(): ?string
    {
        return $this->image_event;
    }

    public function setImageEvent(string $image_event): static
    {
        $this->image_event = $image_event;

        return $this;
    }

    public function getIdCategory(): ?Category
    {
        return $this->idCategory;
    }

    public function setIdCategory(?Category $idCategory): static
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * @return Collection<int, ReservationEvent>
     */
    public function getReservationEvents(): Collection
    {
        return $this->reservationEvents;
    }

    public function addReservationEvent(ReservationEvent $reservationEvent): static
    {
        if (!$this->reservationEvents->contains($reservationEvent)) {
            $this->reservationEvents->add($reservationEvent);
            $reservationEvent->setIdEvent($this);
        }

        return $this;
    }

    public function removeReservationEvent(ReservationEvent $reservationEvent): static
    {
        if ($this->reservationEvents->removeElement($reservationEvent)) {
            // set the owning side to null (unless already changed)
            if ($reservationEvent->getIdEvent() === $this) {
                $reservationEvent->setIdEvent(null);
            }
        }
        return $this;
    }
    

    public function __toString() {
    return $this->nom;
}



}
