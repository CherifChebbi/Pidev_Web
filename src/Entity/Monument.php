<?php

namespace App\Entity;

use App\Repository\MonumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonumentRepository::class)]
class Monument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_monument = null;

    #[ORM\Column(length: 30)]
    private ?string $nom_monument = null;

    #[ORM\Column(length: 50)]
    private ?string $img_monument = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $desc_monument = null;

    #[ORM\ManyToOne(inversedBy: 'monuments')]
    #[ORM\JoinColumn(name: 'id_ville', referencedColumnName: 'id_ville')]
    private ?Ville $villes = null;

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
