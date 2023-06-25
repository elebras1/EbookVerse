<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 2, max: 60)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 2, max: 60)]
    private ?string $prenom = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Constraints\Length(min: 10, max: 1000)]
    private ?string $description = null;

    #[ORM\Column(length: 1)]
    private ?string $etat = null;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Ecrit::class)]
    private Collection $ecrits;

    public function __construct()
    {
        $this->ecrits = new ArrayCollection();
        $this->etat = 'P';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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
            $ecrit->setAuteur($this);
        }

        return $this;
    }

    public function removeEcrit(Ecrit $ecrit): self
    {
        if ($this->ecrits->removeElement($ecrit)) {
            // set the owning side to null (unless already changed)
            if ($ecrit->getAuteur() === $this) {
                $ecrit->setAuteur(null);
            }
        }

        return $this;
    }

}
