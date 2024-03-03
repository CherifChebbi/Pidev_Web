<?php
namespace App\Entity;

use App\Repository\MonumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MonumentRepository::class)
 * @UniqueEntity(fields={"nom_monument"}, message="Ce nom de monument est déjà utilisé.")
 * @IgnoreAnnotation("ORM\Entity")
 */

#[ORM\Entity(repositoryClass: MonumentRepository::class)]
class Monument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_monument = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le nom du monument ne peut pas être vide")]
    #[Assert\Regex(
        pattern:"/^[A-Za-z\s_]*$/",
        message:"Le nom du monument doit commencer par une majuscule et ne peut pas contenir de chiffres"
    )]
    #[Assert\Length(max:30, maxMessage:"Le nom du monument ne peut pas dépasser {{ limit }} caractères")]
    private ?string $nom_monument = null;

    #[ORM\Column(length: 50)]
    private ?string $img_monument = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"La description du monument ne peut pas être vide")]
    private ?string $desc_monument = null;

    #[ORM\ManyToOne(inversedBy: 'monuments')]
    #[ORM\JoinColumn(name: 'id_ville', referencedColumnName: 'id_ville')]
    private ?Ville $villes = null;

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

    public function getVilles(): ?Ville
    {
        return $this->villes;
    }

    public function setVilles(?Ville $villes): static
    {
        $this->villes = $villes;

        return $this;
    }

    public function getIdMonument(): ?int
    {
        return $this->id_monument;
    }

    public function getNomMonument(): ?string
    {
        return $this->nom_monument;
    }

    public function setNomMonument(string $nom_monument): static
    {
        $this->nom_monument = $nom_monument;

        return $this;
    }

    public function getImgMonument(): ?string
    {
        return $this->img_monument;
    }

    public function setImgMonument(string $img_monument): static
    {
        $this->img_monument = $img_monument;

        return $this;
    }

    public function getDescMonument(): ?string
    {
        return $this->desc_monument;
    }

    public function setDescMonument(string $desc_monument): static
    {
        $this->desc_monument = $desc_monument;

        return $this;
    }
}
