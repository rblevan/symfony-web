<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\ORM\Mapping as ORM;

// (Evan) Doctrine, entité (une table)
// lien avec le repository (pour requête).
#[ORM\Entity(repositoryClass: PaysRepository::class)]
// (Evan) force "proj_pays" comme nom de table
#[ORM\Table(name: 'proj_pays')]
class Pays
{
    // (Evan) Clé primaire et auto-incrémente (GeneratedValue).
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 2)]
    private ?string $code = null;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
