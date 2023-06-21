<?php

namespace App\Controller;

use App\Entity\Type;
use App\Entity\Ecrit;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Form\LivreType;
use App\Form\AuteurType;
use App\Form\LivreEditType;
use App\Form\RechercheType;
use App\Service\FileUploader;
use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{

    #[Route('/listelivre/', name: 'listelivre.index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, PaginatorInterface $paginator, Request $request): Response
    {   
        $form = $this->createForm(RechercheType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $request->query->set('recherche', $data['recherche']);
            $request->query->set('genres', $data['genres']);

            $livres = $livreRepository->createQueryBuilder('l')
                ->join('l.types', 't')
                ->join('t.genre', 'g')
                ->join('l.ecrits', 'e')
                ->join('e.auteur', 'a')
                ->where('l.titre LIKE :recherche')
                ->orWhere('a.nom LIKE :recherche')
                ->orWhere('a.prenom LIKE :recherche')
                ->andWhere('l.etat = :etat')
                ->setParameter('recherche', '%' . $data['recherche'] . '%')
                ->setParameter('etat', 'P')
                ->orderBy('l.date', 'DESC');
            foreach( $data['genres'] as $genre)
            {
                $genreId = $genre->getId();
                $livres->andWhere('g.id = :genreId')
                    ->setParameter('genreId', $genreId);
            }
            $livres->getQuery()->getResult();
                
            $livre = $paginator->paginate(
                $livres,
                $request->query->getInt('page', 1),
                6 
            );
            
            return $this->render('pages/listelivre/index.html.twig', ['livres' => $livre, 'form' => $form->createView()]);
        } else {
            $livres = $livreRepository->findBy(['etat' => 'P'], ['date' => 'DESC']);

            $livre = $paginator->paginate(
                $livres,
                $request->query->getInt('page', 1),
                6
            );
        }
        return $this->render('pages/listelivre/index.html.twig', ['livres' => $livre, 'form' => $form->createView()]);
    }

    #[Route('/listelivre/displaylivre/{id}', name: 'displaylivre', methods: ['GET'])]
    public function displaylivre($id, LivreRepository $livreRepository): Response
    {
        $livre = $livreRepository->findOneBy(['id' => $id, 'etat' => 'P']);

        if (!$livre){
            throw $this->createNotFoundException('Le livre demandé n\'existe pas.');
        }

        if($this->getUser())
        {
            $pseudo = $this->getUser()->getUserIdentifier();
        } else {
            $pseudo = null;
        }

        return $this->render('pages/listelivre/displaylivre.html.twig', ['livre' => $livre, 'pseudo' => $pseudo]);
    }

    #[Route('/listelivre/newlivre', name: 'newlivre', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function newlivre(FileUploader $fileUploader, CompteRepository $compteRepository, Request $request, EntityManagerInterface $manager): Response
    {
        if ($this->getUser())
        {
            $compte = $compteRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);
            $livre = new Livre();
            $form = $this->createForm(LivreType::class, $livre);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $livre = $form->getData();
                $livre->setCompte($compte);
                $compte->addLivre($livre);
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $imageFileName = $fileUploader->upload($imageFile, $imageFile->getMimeType());
                    $livre->setImage($imageFileName);
                }

                $ebookFile = $form->get('ebook')->getData();
                if ($ebookFile) {
                    $ebookFileName = $fileUploader->upload($ebookFile, $ebookFile->getMimeType());
                    $livre->setEbook($ebookFileName);
                }

                $auteurs = $form->get('auteur')->getData();
                $genres = $form->get('genre')->getData();

                foreach ($auteurs as $auteur)
                {
                    $ecrit = new Ecrit();
                    $ecrit->setAuteur($auteur);
                    $ecrit->setLivre($livre);
                    
                    $auteur->addEcrit($ecrit);

                    $manager->persist($ecrit);
                }

                foreach ($genres as $genre)
                {
                    $type = new Type();
                    $type->setGenre($genre);
                    $type->setLivre($livre);
                    $genre->addType($type);

                    $manager->persist($type);
                }
                // ajouter dans la bdd
                $manager->persist($livre);
                $manager->flush();

                return $this->redirectToRoute('displaylivre', ['id' => $livre->getId()]);
            }
        } else {
            return $this->redirectToRoute('connexion');
        }
        return $this->render('pages/listelivre/newlivre.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/listelivre/displayebook', name: 'displayebook', methods: ['GET'])]
    public function displayEbook(Request $request): Response
    {
        $ebook = $request->query->get('ebook');

        return $this->render('pages/listelivre/displayebook.html.twig', ['ebook' => $ebook,]);
    }

    #[Route('listelivre/edition/editlivre/{id}', name: 'editlivre', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editlivre(int $id, LivreRepository $livreRepository, CompteRepository $compteRepository, Request $request, FileUploader $fileUploader, EntityManagerInterface $manager): Response
    {
        $livre = $livreRepository->find($id);
        
        // recupere l'image et le ebook au cas ou l'utilisateur ne les a pas upload
        $imageFileName = $livre->getImage();
        $ebookFileName = $livre->getEbook();
        
        if (!$livre){
            throw $this->createNotFoundException('Le livre demandé n\'existe pas.');
        }

        if($this->getUser() !== $livre->getCompte())
        {
            return $this->redirectToRoute('displaylivre', ['id' => $livre->getId()]);
        }

        $compte = $compteRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);

        //recupere les auteurs associés au livre modifiers
        $existingAuteurs = $livre->getEcrits()->map(function ($ecrit) {
            return $ecrit->getAuteur();
        })->toArray();

        //recupere les genres associés au livre modifiers
        $existingGenres = $livre->getTypes()->map(function ($ecrit) {
            return $ecrit->getGenre();
        })->toArray();

        $form = $this->createForm(LivreEditType::class, $livre, [
            'existing_auteurs' => $existingAuteurs,
            'existing_genres' => $existingGenres
        ]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $livre = $form->getData();
            $livre->setCompte($compte);
            $compte->addLivre($livre);
            $imageFile = $form->get('image')->getData();

            if ($imageFile !== null) {
                $imageFileName = $fileUploader->upload($imageFile, $imageFile->getMimeType());
                $livre->setImage($imageFileName);
            } else {
                $livre->setImage($imageFileName);
            }

            $ebookFile = $form->get('ebook')->getData();
            if ($ebookFile !== null) {
                $ebookFileName = $fileUploader->upload($ebookFile, $ebookFile->getMimeType());
                $livre->setEbook($ebookFileName);
            } else {
                $livre->setEbook($ebookFileName);
            }

            $auteurs = $form->get('auteur')->getData();
            $genres = $form->get('genre')->getData();

            foreach ($auteurs as $auteur)
            {
                $existingEcrit = $livre->getEcrits()->filter(function ($ecrit) use ($auteur) {
                    return $ecrit->getAuteur() === $auteur;
                })->first();
        
                if (!$existingEcrit) {
                    $ecrit = new Ecrit();
                    $ecrit->setAuteur($auteur);
                    $ecrit->setLivre($livre);
                    $auteur->addEcrit($ecrit);
                    $manager->persist($ecrit);
                }
            }

            foreach ($genres as $genre)
            {
                $existingType = $livre->getTypes()->filter(function ($type) use ($genre) {
                    return $type->getGenre() === $genre;
                })->first();
        
                if (!$existingType) {
                    $type = new Type();
                    $type->setGenre($genre);
                    $type->setLivre($livre);
                    $genre->addType($type);
                    $manager->persist($type);
                }
            }

            //suppression des ecrits deselectionnés
            foreach ($existingAuteurs as $existingAuteur) {
                //verifie si l'auteur existant dans livre n'est pas selectionné dans la liste
                if (!in_array($existingAuteur, $auteurs, true)) {
                    //filter pour trouver l'ecrit correspondant a l'ecrit existant
                    $existingEcrit = $livre->getEcrits()->filter(function ($ecrit) use ($existingAuteur) {
                        return $ecrit->getAuteur() === $existingAuteur;
                    })->first();
            
                    if ($existingEcrit) {
                        $existingAuteur->removeEcrit($existingEcrit);
                        $manager->remove($existingEcrit);
                    }
                }
            }
            
            //suppression des types deselectionnés
            foreach ($existingGenres as $existingGenre) {
                if (!in_array($existingGenre, $genres, true)) {
                    $existingType = $livre->getTypes()->filter(function ($type) use ($existingGenre) {
                        return $type->getGenre() === $existingGenre;
                    })->first();
            
                    if ($existingType) {
                        $existingGenre->removeType($existingType);
                        $manager->remove($existingType);
                    }
                }
            }

            $manager->persist($livre);
            $manager->flush();

            return $this->redirectToRoute('displaylivre', ['id' => $livre->getId()]);
        }

        return $this->render('pages/listelivre/edition/editlivre.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/listelivre/displayauteur/{id}', name: 'displayauteur', methods: ['GET'])]
    public function displayauteur($id, AuteurRepository $auteurRepository): Response
    {
        $auteur = $auteurRepository->findOneBy(['id' => $id, 'etat' => 'P']);
        if (!$auteur){
            throw $this->createNotFoundException('L\'auteur demandé n\'existe pas.');
        }
        return $this->render('pages/listelivre/displayauteur.html.twig', ['auteur' => $auteur]);
    }

    #[Route('/listelivre/newauteur', name:'newauteur', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function newauteur(Request $request, EntityManagerInterface $manager) : Response
    {
        $auteur = New Auteur;

        $form = $this->createForm(AuteurType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $auteur = $form->getData();

            $manager->persist($auteur);
            $manager->flush();

            return $this->redirectToRoute('displayauteur', ['id' => $auteur->getId()]);
        }

        return $this->render('pages/listelivre/newauteur.html.twig', ['form' => $form]);
    }

    #[Route('/listelivre/edition/editauteur/{id}', name: 'editauteur', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editauteur(int $id ,Request $request, AuteurRepository $auteurRepository, EntityManagerInterface $manager) : Response
    {
        $auteur = $auteurRepository->find($id);

        if (!$auteur){
            throw $this->createNotFoundException('L\'auteur demandé n\'existe pas.');
        }

        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $auteur = $form->getData();

            $manager->persist($auteur);
            $manager->flush();

            return $this->redirectToRoute('displayauteur', ['id' => $id]);
        }

        return $this->render('pages/listelivre/edition/editauteur.html.twig', ['form' => $form]);
    }
}
