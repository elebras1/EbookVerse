<?php

namespace App\Controller\Admin;

use App\Entity\Discussion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DiscussionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Discussion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Discussions')
            ->setPaginatorUseOutputWalkers(true)
            ->setEntityLabelInSingular('Discussion')
            ->setPageTitle("index", "EbookVerse - gestion des discussions")
            ->setPaginatorPageSize(20);
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
