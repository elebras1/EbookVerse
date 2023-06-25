<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Symfony\Component\Validator\Constraints;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 2, max: 40)]
    private ?string $nom = null;

    #[ORM\Column(length: 1)]
    private ?string $etat = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Type::class)]
    private Collection $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->etat = 'A';
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
            $type->setGenre($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getGenre() === $this) {
                $type->setGenre(null);
            }
        }

        return $this;
    }
}
