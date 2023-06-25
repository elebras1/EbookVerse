<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 2, max: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 1000)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 10, max: 1000)]
    private ?string $description = null;

    #[ORM\Column]
    #[Constraints\NotBlank()]
    #[Constraints\Range(min: -4500, max: 2023)]
    private ?int $annee = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(length: 150)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(max: 150)]
    private ?string $image = null;

    #[ORM\Column(length: 150)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(max: 150)]
    private ?string $ebook = null;

    #[ORM\Column(length: 1)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'livre')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'pseudo', name: 'pseudo_id')]
    private ?Compte $compte = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Ecrit::class)]
    private Collection $ecrits;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Type::class)]
    private Collection $types;

    public function __construct()
    {
        $this->ecrits = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->date = new DateTimeImmutable();
        $this->etat = 'P';
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEbook(): ?string
    {
        return $this->ebook;
    }

    public function setEbook(?string $ebook): self
    {
        $this->ebook = $ebook;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * @return Collection<int, Ecrit>
     */
    public function getEcrits(): Collection
    {
        return $this->ecrits;
    }

    public function addEcrit(Ecrit $ecrit): self
    {
        if (!$this->ecrits->contains($ecrit)) {
            $this->ecrits->add($ecrit);
            $ecrit->setLivre($this);
        }

        return $this;
    }

    public function removeEcrit(Ecrit $ecrit): self
    {
        if ($this->ecrits->removeElement($ecrit)) {
            // set the owning side to null (unless already changed)
            if ($ecrit->getLivre() === $this) {
                $ecrit->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->setLivre($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getLivre() === $this) {
                $type->setLivre(null);
            }
        }

        return $this;
    }
}
