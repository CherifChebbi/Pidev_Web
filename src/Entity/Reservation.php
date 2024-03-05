<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
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
    private ?string $nom = null;

    /**
 * @Assert\NotBlank(message="L'email est obligatoire")
 * @Assert\Email(
 *     message = "L'email '{{ value }}' n'est pas valide.",
 *     mode = "html5"
 * )
 * @ORM\Column(type="string", length=255)
 */

    #[ORM\Column(length: 255)]
    private ?string $email = null;
    /**
     * @Assert\NotBlank(message="La date de réservation est obligatoire.")
     * @ORM\Column(type=Types::DATE_MUTABLE)
     */

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @Assert\NotBlank(message="Le nombre de personnes est obligatoire.")
     * @Assert\Positive(message="Le nombre de personnes doit être positif.")
     * @ORM\Column
     */

    #[ORM\Column]
    public ?int $nbr_personne = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNbrPersonne(): ?int
    {
        return $this->nbr_personne;
    }

    public function setNbrPersonne(int $nbr_personne): static
    {
        $this->nbr_personne = $nbr_personne;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
