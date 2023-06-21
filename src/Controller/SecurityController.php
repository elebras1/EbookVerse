<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\CompteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/utilisateur/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUserName = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('utilisateur/connexion.html.twig', ['error' => $error, 'lastUserName' => $lastUserName]);
    }

    #[Route('/utilisateur/deconnexion', name: 'deconnexion', methods:['GET'])]
    public function deconnexion()
    {
        //nothing here
    }

    #[Route('/utilisateur/inscription', name: 'inscription', methods: ['GET','POST'])]
    public function inscription(Request $request, EntityManagerInterface $manager): Response
    {
        $compte = new Compte();

        $form = $this->createForm(CompteType::class, $compte);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compte = $form->getData();
            $profil = $compte->getProfil();

            $existingCompte = $manager->getRepository(Compte::class)->findOneBy(['pseudo' => $compte->getPseudo()]);
            if ($existingCompte) {
                $this->addFlash('error', 'Pseudo déjà existant.');
                return $this->redirectToRoute('inscription');
    }

            $profil->setCompte($compte);
            
            $manager->persist($compte);
            $manager->persist($profil); 
            $manager->flush();
        
            return $this->redirectToRoute('connexion');
        }
        
        return $this->render('utilisateur/inscription.html.twig', ['form' => $form->createView(),]);
    }
}