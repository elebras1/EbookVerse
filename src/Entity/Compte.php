<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\CompteListener'])]
class Compte implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 60)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 60)]
    private ?string $mot_de_passe = null;

    #[ORM\OneToOne(mappedBy: 'compte')]
    private ?Profil $profil = null;

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Discussion::class)]
    private Collection $discussion;

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Livre::class)]
    private Collection $livre;

    public function __construct()
    {
        $this->discussion = new ArrayCollection();
        $this->livre = new ArrayCollection();
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Discussion>
     */
    public function getDiscussion(): Collection
    {
        return $this->discussion;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->discussion->contains($discussion)) {
            $this->discussion->add($discussion);
            $discussion->setCompte($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->discussion->removeElement($discussion)) {
            // set the owning side to null (unless already changed)
            if ($discussion->getCompte() === $this) {
                $discussion->setCompte(null);
            }
        }

        return $this;
    }

    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livre->contains($livre)) {
            $this->livre->add($livre);
            $livre->setCompte($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getCompte() === $this) {
                $livre->setCompte(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
{
    $roles = [];
    
    if ($this->profil !== null) {
        $roles = $this->profil->getRoles();
    }
    
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
}

    public function eraseCredentials()
    {
        // rien
    }

    public function getUserIdentifier(): string
    {
        return $this->pseudo;
    }

    public function getPassword(): string
    {
        return $this->mot_de_passe;
    }

    public function __toString(): string
    {
        return $this->pseudo;
    }
    
}

?>
