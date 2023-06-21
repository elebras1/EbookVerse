<?php

namespace App\EntityListener;

use App\Entity\Compte;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompteListener
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function prePersist(Compte $compte)
    {
        $this->encodeMotDePasse($compte);
    }

    public function preUpdate(Compte $compte)
    {
        $this->encodeMotDePasse($compte);
    }

    public function encodeMotDePasse(Compte $compte)
    {
        $compte->setMotDePasse(
            $this->hasher->hashPassword(
                $compte,
                $compte->getMotDePasse()
            )
        );
    }
}