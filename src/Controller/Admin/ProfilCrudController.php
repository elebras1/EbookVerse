<?php

namespace App\Controller\Admin;

use App\Entity\Profil;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProfilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profil::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorUseOutputWalkers(true)
            ->setEntityLabelInPlural('Profils')
            ->setEntityLabelInSingular('Profil')
            ->setPageTitle("index", "EbookVerse - gestion des profils")
            ->setPaginatorPageSize(20);
    }

/*    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Pseudo'),
            TextField::new('Mot de passe')
        ];
    }
*/
}
