<?php

namespace App\Entity;

use App\Repository\ReservationEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationEventRepository::class)]
class ReservationEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\- ]+$/',
        message: 'Le nom ne peut contenir que des lettres, des tirets et des espaces'
        )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'email ne peut pas être vide')]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas valide')]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $num_tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de réservation ne peut pas être vide')]
    #[Assert\Type('\DateTimeInterface', message: 'La date de réservation doit être une date valide')]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\ManyToOne(inversedBy: 'reservationEvents')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Event $id_event = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(int $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): static
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getIdEvent(): ?Event
    {
        return $this->id_event;
    }

    public function setIdEvent(?Event $id_event): static
    {
        $this->id_event = $id_event;

        return $this;
    }

    public function __toString()
{
    return sprintf(
        'Réservation #%d - Nom: %s, Email: %s, Téléphone: %d, Date de réservation: %s, Événement: %s',
        $this->id,
        $this->nom,
        $this->email,
        $this->num_tel,
        $this->date_reservation ? $this->date_reservation->format('Y-m-d') : 'N/A',
        $this->id_event ? $this->id_event->getTitre() : 'N/A'
    );
}

}
