<?php

namespace App\Entity;

use App\Repository\EcritRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcritRepository::class)]
class Ecrit
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ecrits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livre $livre = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ecrits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auteur $auteur = null;

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
