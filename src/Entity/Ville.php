<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 * @UniqueEntity(fields={"nom_ville"}, message="Ce nom de ville est déjà utilisé.")
 * @IgnoreAnnotation("ORM\Entity")
 */
#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_ville = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le nom du monument ne peut pas être vide")]
    #[Assert\Regex(
        pattern:"/^[A-Za-z\s_]*$/",
        message:"Le nom du monument doit commencer par une majuscule et ne peut pas contenir de chiffres"
    )]
    #[Assert\Length(max:30, maxMessage:"Le nom du monument ne peut pas dépasser {{ limit }} caractères")]
    public ?string $nom_ville = null;

    #[ORM\Column(length: 50)]
    public ?string $img_ville = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"La description de la ville ne peut pas être vide")]
    public ?string $desc_ville = null;
    
    #[ORM\ManyToOne(inversedBy: 'villes')]
    #[ORM\JoinColumn(name: 'id_pays', referencedColumnName: 'id_pays')]
    private ?Pays $pays = null;

    #[ORM\Column]
    public ?int $nb_monuments = null;

    #[ORM\OneToMany(mappedBy: 'villes', targetEntity: Monument::class ,cascade: ['remove'])]
    private Collection $monuments;

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
    
    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }
    
    /**
     * @return Collection<int, Monument>
     */
    public function getMonuments(): Collection
    {
        return $this->monuments;
    }
    

    public function getIdVille(): ?int
    {
        return $this->id_ville;
    }

    public function getNomVille(): ?string
    {
        return $this->nom_ville;
    }

    public function setNomVille(string $nom_ville): static
    {
        $this->nom_ville = $nom_ville;

        return $this;
    }

    public function getImgVille(): ?string
    {
        return $this->img_ville;
    }

    public function setImgVille(string $img_ville): static
    {
        $this->img_ville = $img_ville;

        return $this;
    }

    public function getDescVille(): ?string
    {
        return $this->desc_ville;
    }

    public function setDescVille(string $desc_ville): static
    {
        $this->desc_ville = $desc_ville;

        return $this;
    }

    public function getNbMonuments(): ?int
    {
        return $this->nb_monuments;
    }

    public function setNbMonuments(int $nb_monuments): static
    {
        $this->nb_monuments = $nb_monuments;

        return $this;
    }
}
