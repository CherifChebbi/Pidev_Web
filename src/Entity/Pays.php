<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_pays = null;

    #[ORM\Column(length: 30)]
    private ?string $nom_pays = null;

    #[ORM\Column(length: 30)]
    private ?string $img_pays = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $desc_pays = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\OneToMany(mappedBy: 'pays', targetEntity: Ville::class)]
    private Collection $villes;
    
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

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }
   
}
