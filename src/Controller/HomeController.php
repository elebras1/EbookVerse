<?php
namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionType;
use App\Repository\LivreRepository;
use App\Repository\CompteRepository;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/','home.index', methods: ['GET', 'POST'])]
    public function index(LivreRepository $livreRepository, DiscussionRepository $discussionRepository, CompteRepository $compteRepository, PaginatorInterface $paginator, Request $request, EntityManagerInterface $manager): Response
    {
        $livres = $livreRepository->findBy(['etat' => 'P'], ['date' => 'DESC']);
        $livre = $paginator->paginate(
            $livres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        $discussion = $discussionRepository->findBy(['etat' => 'P'], ['date' => 'DESC'], 10);
        $compte = $compteRepository->findAll();

        $dis = new Discussion();
        $form = $this->createForm(DiscussionType::class, $dis);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$this->getUser())
            {
            return $this->redirectToRoute('connexion');
            }
            $compte = $compteRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);
            $message = $form->getData();
            $compte->addDiscussion($message);
            // ajouter dans la bdd
            $manager->persist($compte);
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('home.index', ['_fragment' => 'discussion']);
        }

        return $this->render('pages/home.html.twig',['livre' => $livre, 'livres' => $livres, 'discussion' => $discussion, 'compte' => $compte, 'form' => $form->createView()]);
    }
}

?>