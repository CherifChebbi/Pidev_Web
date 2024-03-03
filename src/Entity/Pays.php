<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PaysRepository::class)
 * @UniqueEntity(fields={"nom_pays"}, message="Ce nom de pays est déjà utilisé.")
 * @IgnoreAnnotation("ORM\Entity")
 */

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_pays = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le nom du monument ne peut pas être vide")]
    #[Assert\Regex(
        pattern:"/^[A-Za-z\s_]*$/",
        message:"Le nom du monument doit commencer par une majuscule et ne peut pas contenir de chiffres"
    )]
    #[Assert\Length(max:30, maxMessage:"Le nom du monument ne peut pas dépasser {{ limit }} caractères")]
    public ?string $nom_pays = null;

    #[ORM\Column(length: 30)]
    public ?string $img_pays = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"La description du pays ne peut pas être vide")]
    public ?string $desc_pays = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La langue ne peut pas être vide")]
    public ?string $langue = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Continent ne peut pas être vide")]
    public ?string $continent = null;

    #[ORM\Column]
    public ?int $nb_villes = null;

    #[ORM\OneToMany(mappedBy: 'pays', targetEntity: Ville::class, cascade: ['remove'])]
    private Collection $villes;

    //MAPS
    #[ORM\Column]
    private float $latitude;
    #[ORM\Column]
    private float $longitude;

    // Add getters and setters for the latitude and longitude attributes
    public function getlatitude(): ?int
    {
        return $this->latitude;
    }
    public function getlongitude(): ?int
    {
        return $this->longitude;
    }
    //set
    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }
    public function setLongitude( float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
    //coordonnes
    public function getCoordonnees(): array
    {
        return [
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude(),
        ];
    }

    public function setCoordonnees(array $coordonnees): void
    {
        $this->setLatitude($coordonnees['lat']);
        $this->setLongitude($coordonnees['lng']);
    }
    
    
    /**
     * @return Collection<int, Ville>
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }


    public function __construct()
    {
        $this->villes = new ArrayCollection();
    }
    public function getIdPays(): ?int
    {
        return $this->id_pays;
    }
    public function getNomPays(): ?string
    {
        return $this->nom_pays;
    }

    public function setNomPays(string $nom_pays): static
    {
        $this->nom_pays = $nom_pays;

        return $this;
    }

    public function getImgPays(): ?string
    {
        return $this->img_pays;
    }

    public function setImgPays(string $img_pays): static
    {
        $this->img_pays = $img_pays;

        return $this;
    }

    public function getDescPays(): ?string
    {
        return $this->desc_pays;
    }

    public function setDescPays(string $desc_pays): static
    {
        $this->desc_pays = $desc_pays;

        return $this;
    }
    public function getContinent(): ?string
    {
        return $this->continent;
    }
    public function setContinent(string $continent): static
    {
        $this->continent = $continent;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }
    public function getNbVilles(): ?int
    {
        return $this->nb_villes;
    }

    public function setNbVilles(int $nb_villes): static
    {
        $this->nb_villes = $nb_villes;

        return $this;
    }

}
