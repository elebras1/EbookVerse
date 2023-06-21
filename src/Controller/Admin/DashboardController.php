<?php

namespace App\Controller\Admin;

use App\Entity\Ecrit;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Discussion;
use App\Entity\Configuration;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EbookVerse - administration')
            ->renderContentMaximized()
            ->setLocales(['fr', 'en']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('EbookVerse', 'fa fa-globe', '/');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Comptes', 'fa fa-user', Compte::class);
        yield MenuItem::linkToCrud('Profils', 'fa fa-address-card-o', Profil::class);
        yield MenuItem::linkToCrud('Livres', 'fa fa-book', Livre::class);
        yield MenuItem::linkToCrud('Auteurs', 'fa fa-address-book', Auteur::class);
        yield MenuItem::linkToCrud('Discussions', 'fa fa-comments-o', Discussion::class);
        yield MenuItem::linkToCrud('Genres', 'fa fa-square-o', Genre::class);
        yield MenuItem::linkToCrud('Configuration', 'fa fa-sun-o', Configuration::class);
    }
}
