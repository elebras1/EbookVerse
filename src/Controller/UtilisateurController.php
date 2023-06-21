<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\ProfilType;
use App\form\UserPasswordType;
use App\Repository\CompteRepository;
use App\Repository\ConfigurationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurController extends AbstractController
{
    #[Route('utilisateur/displayprofil/{id}', name:'displayprofil')]
    #[IsGranted('ROLE_USER')]
    public function displayprofil($id, CompteRepository $compteRepository): Response
    {
        $compte = $compteRepository->find($id);
        return $this->render('utilisateur/displayprofil.html.twig', ['compte' => $compte]);
    }

    #[Route('/utilisateur/edition/editprofil/{id}', name: 'editprofil')]
    public function index(Compte $compte, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('connexion');
        }

        if($this->getUser() !== $compte)
        {
            return $this->redirectToRoute('home.index');
        }
        
        $profil = $compte->getProfil();
        $form = $this->createForm(ProfilType::class, $profil);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $profil = $form->getData();
            $manager->persist($profil);
            $manager->flush();

            $this->addFlash(
                'success',
                'les informations de votre compte ont bien été modifié'
            );

            return $this->redirectToRoute('home.index');
        } else {
            $this->addFlash(
                'warning',
                'informations renseignées incorrectes.'
            );
        }

        return $this->render('utilisateur/edition/editprofil.html.twig', ['form' => $form->createView()]);
    }

    #[Route('utilisateur/edition/editmotdepasse/{id}', name: 'editmotdepasse')]
    #[IsGranted('ROLE_USER')]
    public function editmotdepasse(Compte $compte, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if($this->getUser() !== $compte)
        {
            return $this->redirectToRoute('home.index');
        }
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        $formData = $form->getData();
        if($form->isSubmitted() && $form->isValid())
        {
            if($hasher->isPasswordValid($compte, $formData['mot_de_passe']))
            {
                $compte->setMotDePasse($formData['new_mot_de_passe']);
                $manager->persist($compte);
                $manager->flush();
            }
            $this->addFlash(
                'success',
                'le mot de passe de votre compte a bien été modifié'
            );
            return $this->redirectToRoute('home.index');
        } else {
            $this->addFlash(
                'warning',
                'mot de passe renseigné incorrecte.'
            );
        }
        return $this->render('utilisateur/edition/editmotdepasse.html.twig', ['form' => $form->createView()]);
    }
    public function navbar(Security $security, ConfigurationRepository $configurationRepository): Response
    {
        $compte = $security->getUser();
        $configuration = $configurationRepository->findAll();

        return $this->render('partials/_navbar.html.twig', ['compte' => $compte, 'configuration' => $configuration]);
    }
}
