<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    #[ORM\Column(length: 60, nullable: true)]
    #[Constraints\Length(min: 2, max: 60)]
    private ?string $prenom = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Constraints\Length(min: 2, max: 60)]
    private ?string $nom = null;

    #[ORM\Column(length: 1)]
    private ?string $validite = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Id]
    #[Constraints\NotBlank()]
    #[ORM\OneToOne(targetEntity: Compte::class)]
    #[ORM\JoinColumn(name: 'cpt_pseudo', referencedColumnName: 'pseudo')]
    private ?Compte $compte = null;

    public function __construct()
    {
        $this->date_creation = new DateTimeImmutable();
        $this->validite = 'A';
        $this->roles = ['ROLE_USER'];
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getValidite(): ?string
    {
        return $this->validite;
    }

    public function setValidite(string $validite): self
    {
        $this->validite = $validite;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function __toString(): string
    {
        return 'truc';
    }

}

